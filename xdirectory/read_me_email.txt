//Add this to under Email stuff	

$from = "From: info@oldmillmarketplace.com"; 
$to = $email; 
$subject = $subject; 
$body = $message; 

if(mail($to,$subject,$body,$from)) echo ""; 
else echo ""; 