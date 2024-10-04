<?php
/**
 * PHPWord
 *
 * Copyright (c) 2011 PHPWord
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPWord
 * @package    PHPWord
 * @copyright  Copyright (c) 010 PHPWord
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 * @version    Beta 0.6.3, 08.07.2011
 */


/**
 * PHPWord_DocumentProperties
 *
 * @category   PHPWord
 * @package    PHPWord
 * @copyright  Copyright (c) 2009 - 2011 PHPWord (http://www.codeplex.com/PHPWord)
 */
class PHPWord_Template {
    
    /**
     * ZipArchive
     * 
     * @var ZipArchive
     */
    private $_objZip;
    
    /**
     * Temporary Filename
     * 
     * @var string
     */
    private $_tempFileName;
    
    /**
     * Document XML
     * 
     * @var string
     */
    private $_documentXML;
    
    
    /**
     * Create a new Template Object
     * 
     * @param string $strFilename
     */
    public function __construct($strFilename) {
        $path = dirname($strFilename);
        $this->_tempFileName = $path.DIRECTORY_SEPARATOR.time().'.docx';
        
        copy($strFilename, $this->_tempFileName); // Copy the source File to the temp File

        $this->_objZip = new ZipArchive();
        $this->_objZip->open($this->_tempFileName);
        
		//$this->_documentXML = preg_replace_callback('/(\$.*\})/U',"self::striptags", $this->_documentXML );
        $this->_documentXML = $this->_objZip->getFromName('word/document.xml');
    }
    
    /**
     * Set a Template value
     * 
     * @param mixed $search
     * @param mixed $replace
     */
	
	private static function striptags($matches)
	{
		return strip_tags($matches[1]);
	}
	
    public function setValue($search, $replace) {
        //echo $search."<br>";
		/*if(substr($search, 0, 2) !== '${' && substr($search, -1) !== '}') {
            $search = '${'.$search.'}';
        }*/
		
        if(!is_array($replace)) {
            $replace = utf8_encode($replace);
			$replace = str_replace('&','&amp;',$replace);
        }
        //echo $search." - ".$replace."<br>";
        $this->_documentXML = str_replace($search, $replace, $this->_documentXML);
		//echo "<br><br>".$this->_documentXML."<br><br>";
		
		if(substr($search, 0, 2) !== '${' && substr($search, -1) !== '}') {
            $search = '${'.$search.'}';
        }
		
		//echo $search." - ".$replace."<br>";
		
        preg_match_all('/\$[^\$]+?}/', $this->_documentXML, $matches);

		//$newXML = str_replace('&','dan',$this->_documentXML);
		
		for ($i=0;$i<count($matches[0]);$i++){

			$matches_new[$i] = preg_replace('/(<[^<]+?>)/','', $matches[0][$i]);
			
			//$matches_new[$i] = preg_replace("/&#?[a-z0-9]+;/i","",$matches_new[$i]);
			
			//echo 'batas'.$matches[0][$i].'batas<br>'.$matches_new[$i].'<br>';
			
			$this->_documentXML = str_replace($matches[0][$i], $matches_new[$i], $this->_documentXML);//str_replace('&','&amp;',$this->_documentXML));
		}
		
		//$replace = '#65120';
		//echo $this->_documentXML.'---<br>'.$matches_new[$i].'<br>';
		//echo $search.'---<br>';//.$matches_new[$i].'<br>';
		//echo $search." - ".$replace." - ".$this->_documentXML."<br><br>";
		
		//echo $search." - ".$replace."<br>".'---<br>'.$this->_documentXML.'-----<br>';
		
		$this->_documentXML = str_replace($search, $replace, $this->_documentXML);
		$this->_documentXML = str_replace('${', '', $this->_documentXML);
		$this->_documentXML = str_replace('}', '', $this->_documentXML);
		$this->_documentXML = str_replace('\n', '<w:br/>', $this->_documentXML);
		
		//$this->_documentXML = str_replace('\n', '<br>', $this->_documentXML);
		//echo $replace.'---<br><br>'.$this->_documentXML.'---<br><br>';
		//echo "<br><br>".$this->_documentXML."<br><br>";
    }
    
    /**
     * Save Template
     * 
     * @param string $strFilename
     */
    public function save($strFilename) {
        if(file_exists($strFilename)) {
            unlink($strFilename);
        }
        
        $this->_objZip->addFromString('word/document.xml', $this->_documentXML);
        
        // Close zip file
        if($this->_objZip->close() === false) {
            throw new Exception('Could not close zip file.');
        }
        
        rename($this->_tempFileName, $strFilename);
    }
}


?>