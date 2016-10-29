<?php

function sendMail($newLinks)
{
    $username = 'testymacttesterson@gmail.com';
    $password = getenv('GMAIL_PASSWORD');

    $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl")
        ->setUsername($username)
        ->setPassword($password);

    $mailer = Swift_Mailer::newInstance($transport);

    $message = Swift_Message::newInstance(sprintf('%d New Places in Cork', count($newLinks)))
        ->setFrom(array('testymacttesterson@gmail.com' => 'Test Account'))
        ->setTo(array('michaeldevery@gmail.com' => 'Michael Devery'))
        ->addCc('amy.keeley.ak@gmail.com', "Amy Healy")
        ->setBody(getBody($newLinks), 'text/html');

    $mailer->send($message);
}

function getBody($newLinks)
{
    $body = '<h2>New places</h2>';
    $body .= "<ul>";

    foreach ($newLinks as $link) {
        $body .= sprintf("<li>%s%s</li>", HOST, $link);
    }

    $body .= "</ul>";

    return $body;
}