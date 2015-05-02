<?php
function encrypt($message) {
    return base64_encode(
        mcrypt_encrypt( 
            MCRYPT_RIJNDAEL_128,
            md5("ABCDEF123456789"),
            $message,  
            MCRYPT_MODE_CFB,
            "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0"
        )
    );
}
echo encrypt($_GET[data]);
?>
