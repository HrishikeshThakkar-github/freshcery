<?php
require('stripe-php-master/init.php');

$publishableKey="pk_test_51R9JkcR6teQRIca2HU8eq41bjrjhmfpfuS9OdmOeRJVfa9lFgUMkm99v0Z3hnSeQ1gpOmG5tKQMGi8QvZp9Am0AM00KRpLDr0f";

$secretKey="sk_test_51R9JkcR6teQRIca2VKQFor3nvcE3MHxCoX30oDuyF8KgwUgeNqsgn0ggGpK2rDJ108UnW6F5mqihzJrQg5sHwyOV00NipkdhE3";

\Stripe\Stripe::setApiKey($secretKey);
?>