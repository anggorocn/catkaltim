function checkNullValue(textData)

{

   return textData==null ? '' : textData;

}



function roundStringNumberWithoutTrailingZeroes (num, dp) {

    if (arguments.length != 2) throw new Error("2 arguments required");



    num = String(num);

    if (num.indexOf('e+') != -1) {

        // Can't round numbers this large because their string representation

        // contains an exponent, like 9.99e+37

        throw new Error("num too large");

    }

    if (num.indexOf('.') == -1) {

        // Nothing to do

        return num;

    }



    var parts = num.split('.'),

        beforePoint = parts[0],

        afterPoint = parts[1],

        shouldRoundUp = afterPoint[dp] >= 5,

        finalNumber;



    afterPoint = afterPoint.slice(0, dp);

    if (!shouldRoundUp) {

        finalNumber = beforePoint + '.' + afterPoint;

    } else if (/^9+$/.test(afterPoint)) {

        // If we need to round up a number like 1.9999, increment the integer

        // before the decimal point and discard the fractional part.

        finalNumber = Number(beforePoint)+1;

    } else {

        // Starting from the last digit, increment digits until we find one

        // that is not 9, then stop

        var i = dp-1;

        while (true) {

            if (afterPoint[i] == '9') {

                afterPoint = afterPoint.substr(0, i) +

                             '0' +

                             afterPoint.substr(i+1);

                i--;

            } else {

                afterPoint = afterPoint.substr(0, i) +

                             (Number(afterPoint[i]) + 1) +

                             afterPoint.substr(i+1);

                break;

            }

        }



        finalNumber = beforePoint + '.' + afterPoint;

    }



    // Remove trailing zeroes from fractional part before returning

    return finalNumber.replace(/0+$/, '')

}



function replacenum(num)

{

    // num.toString().replace(/\./g, ',');

    return num.toString().replace(/\,/g, '');

}



function codeexceltochar( n ) {

    var result = '';

    do {

        result = (n % 26 + 10).toString(36) + result;

        n = Math.floor(n / 26) - 1;

    } while (n >= 0)

    return result.toUpperCase();

}

function removetrailingzeros(value) {
    value = value.toString();

    // # if not containing a dot, we do not need to do anything
    if (value.indexOf('.') === -1) {
        return value;
    }

    // # as long as the last character is a 0 or a dot, remove it
    while((value.slice(-1) === '0' || value.slice(-1) === '.') && value.indexOf('.') !== -1) {
        value = value.substr(0, value.length - 1);
    }
    return value;
}

function round(number, precision) {
    var pair = (number + 'e').split('e')
    var value = Math.round(pair[0] + 'e' + (+pair[1] + precision))
    pair = (value + 'e').split('e')
    return +(pair[0] + 'e' + (+pair[1] - precision))
}

function setvaluedatafloat(val)
{
    if(val == "") val= 0;
    return parseFloat(replacenum(val));
}