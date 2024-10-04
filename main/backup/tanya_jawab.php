<?php
include_once("../WEB/classes/utils/UserLogin.php");

$userLogin->checkLoginPelamar();

?>
<link rel="stylesheet" href="../WEB/css/bubble.css" type="text/css" />

<div class="col-lg-8">

	<div id="judul-halaman">Tanya Jawab</div>
	

    <div class="konten-10">
	    <div class="alert alert-info"><i class="fa fa-info-circle"></i> Silahkan menggunakan fasilitas ini apabila anda ingin bertanya seputar rekrutmen.</div>
    </div>
    <!--<div class="container">-->
        <div class="row area-chat">
            <div class="col-md-12">
                <div class="panel panel-primary area-chat-inner">
                    <div class="panel-heading" id="accordion">
                        <!--<span class="glyphicon glyphicon-comment"></span> Chat-->
                        <form id="daddy-shoutbox-form" action="../json/daddy_shoutbox_json.php?action=add" method="post" style="width:100%;"> 
                            <div class="input-group">
                                <input type="hidden" name="nickname" value="<?=$userLogin->userNoRegister?>" />
                                <input type="hidden" name="reqId" value="<?=$userLogin->userPelamarId?>" readonly /> 
                                <input type="hidden" name="reqHalaman" value="0" readonly />
                                <input type="hidden" name="reqKode" value="0" readonly />                              
                                <input id="btn-input" type="text" name="message" class="form-control input-sm input-chat" placeholder="Type your message here..." />
                                <span class="input-group-btn">
                                    <button class="btn btn-warning btn-sm" id="btn-chat">
                                        Send</button>
                                </span>
                                <input type="submit" value="Submit"  style="display:none" />
                            </div>
                            </form>
                    </div>

					<script type="text/javascript">
                          var count = 0;
                          var files = '';
                          var lastTime = 0;
                          
                          function prepare(response) {
                            var d = new Date();
                            count++;
                            d.setTime(response.time*1000);
                            var mytime = d.getHours()+':'+d.getMinutes()+':'+d.getSeconds();
                            var string = '<div class="shoutbox-list" id="list-'+count+'">'
                                + '<span class="shoutbox-list-time">'+mytime+'</span>'
                                + '<span class="shoutbox-list-nick">'+response.nickname+':</span>'
                                + '<span class="shoutbox-list-message">'+response.message+'</span>'
                                +'</div>';
                            if(response.nickname == '<?=$userLogin->userNoRegister?>')
                            {						
                                var string = '<li class="right clearfix">' +
                                    '<span class="chat-img pull-right">' +
                                        '<img src="../WEB/images/chat-me.png" class="img-circle" />' +
                                    '</span>' +
                                    '<div class="chat-body clearfix">' +
                                    	'<div class="triangle-isosceles right">' +
                                        	'<div class="header">' +
                                                '<small class=" text-muted"><span class="glyphicon glyphicon-time"></span>'+response.waktu+'</small>' +
                                                '<strong class="pull-right primary-font">'+response.nickname+'</strong>' +
                                            '</div>' +
                                            '<p>' + response.message +
                                            '</p>' +
                                        '</div>' +
                                    '</div>' +
                                '</li>';  
                            }
                            else
                            {                                						
                                var string = '<li class="left clearfix">' +
                                    '<span class="chat-img pull-left">' +
                                        '<img src="../WEB/images/chat-u.png" class="img-circle" />' +
                                    '</span>' +
                                    '<div class="chat-body clearfix">' +
                                    	'<div class="triangle-isosceles left">' +
                                        	'<div class="header">' +
                                                '<strong class="primary-font">'+response.nickname+'</strong>'  +
                                                '<small class="pull-right text-muted">' +
                                                    '<span class="glyphicon glyphicon-time"></span>'+response.waktu+'' +
												'</small>' +
                                            '</div>' +
                                            '<p>' + response.message +
                                            '</p>' +
                                        '</div>' +
                                    '</div>' +
                                '</li>';									  
                            }						  
                            return string;
                          }


                          function success(response, status)  { 
                            if(status == 'success') {
                              lastTime = response.time;
                              $('#daddy-shoutbox-response').html('<img src="'+files+'images/accept.png" />');
                              $('.chat').prepend(prepare(response));
                              $('#btn-input').val('');
                              $('#btn-input').focus();										
                              $('#list-'+count).fadeIn('slow');
                              timeoutID = setTimeout(refresh, 3000);
                            }
                          }
                          
                          function validate(formData, jqForm, options) {
                            for (var i=0; i < formData.length; i++) { 
                                if (!formData[i].value) {
                                    alert('Please fill in all the fields'); 
                                    document.location.reload();
                                    return false; 
                                } 
                            } 
                            $('#daddy-shoutbox-response').html('<img src="'+files+'images/loader.gif" />');
                            clearTimeout(timeoutID);
                          }
                  
                          function refresh() {
                            $.getJSON("../json/daddy_shoutbox_json.php?reqId=<?=$userLogin->userPelamarId?>&action=view&time="+lastTime, function(json) {
                              if(json.length) {
                                for(i=0; i < json.length; i++) {
                                  $('.chat').prepend(prepare(json[i]));
                                }
                                var j = i-1;
                                lastTime = json[j].time;
                              }
                              //alert(lastTime);
                            });
                            timeoutID = setTimeout(refresh, 3000);
                          }
                          
                          // wait for the DOM to be loaded 
                          $(document).ready(function() { 
                              var options = { 
                                dataType:       'json',
                                beforeSubmit:   validate,
                                success:        success
                              }; 
                              $('#daddy-shoutbox-form').ajaxForm(options);
                              timeoutID = setTimeout(refresh, 100);
                          });
                    </script>       
                                        
                    <div>
                        <div class="panel-body">
                            <ul class="chat">

                               
                                
                            </ul>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    <!--</div>-->

</div>

    <!-- SHOUTBOX -->
	<script type="text/javascript" src="../WEB/lib/shoutbox2/javascript/jquery.js"></script>
    <script type="text/javascript" src="../WEB/lib/shoutbox2/javascript/jquery.form.js"></script>