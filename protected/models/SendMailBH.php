<?php
//use GuzzleHttp\Psr7\Response;

class SendMailBH
{
    public static function notification($from, $subject, $body, $emails, $addCC = [])
    {
        $mail = Yii::app()->Smtpmail;
        $mail->SetFrom($from, 'Notifiaciones Biblihorizonte');
        $mail->FromName = $from;
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->IsHTML(true);
        foreach ($emails as $rsTo) {
            $mail->AddAddress($rsTo, "");
        }
        if (isset($addCC)) {
            foreach ($addCC as $rsCopy) {
                $mail->addCC($rsCopy);
            }
        }
        $mail->CharSet = 'UTF-8';

        if (!$mail->Send()) {
            return Responses::getError([$mail->ErrorInfo]);
        } else {
            return Responses::getOk();            
        }
    }
}
