<?php
namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

Class MailHelper
{
    private static function esc(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

    public static function sendContactMail(string $contact_email, string $contact_name, string $contact_message, string $contact_sendMe) 
    {
        $contact_name = MailHelper::esc($contact_name);
        $contact_message = MailHelper::esc($contact_message);
        
        try {

            try {
                $mail = new PHPMailer(true);
                //$mail->SMTPDebug = 2;

            }catch (Exception $e) {        
                if (DEBUG) {
                    echo "[MAILER ERROR1] : {$mail->ErrorInfo}}";
                    exit;
                }
            }            
            
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';                                 //Pièce jointes ? A TESTER
            $mail->isSMTP();
            
            $mail->Host = 'smtp.gmail.com';                                    //Adresse IP ou DNS du serveur SMTP
            $mail->Port = '587';                                    //Port TCP du serveur SMTP
            $mail->SMTPAuth = true;                                     //Utiliser l'identification

            if($mail->SMTPAuth){
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;     //Protocole de sécurisation des échanges avec le SMTP ('tls'->plus de compatibilité) / 'ssl'->plus secure)
                $mail->Username = SMTP_USER;
                $mail->Password = SMTP_PASS;
            }

            $mail->From = SMTP_FROM;                                    //L'email à afficher pour l'envoi (adresse email servira de référence pour répondre)
            $mail->FromName = SMTP_FROM_NAME;                           //L'alias à afficher pour l'envoi (optionnel)
            //$mail->addCC(address: $contact_email)
            if($contact_sendMe == 'sendMe') {
                $mail->addBCC(address: $contact_email);             // Copie cachée
            }               

            $mail->addAddress(SMTP_USER);

            $mail->isHTML(true);
            $mail->Subject = 'Ecoride Page Contact';
            $mail->Body = "
                <h2>Message envoyé depuis la page contact d'Ecoride</h2>
                <p><strong>NOM : </strong>$contact_name</p>
                <p><strong>EMAIL : </strong>$contact_email</p>
                <p><strong>MESSAGE : </strong></br>$contact_message</p>
            ";
            $mail->AltBody = "$contact_message";                        //alt text non formatté html

            $mail->send();

            return true;

        } catch (Exception $e) {        
            if (DEBUG) {                
                echo "[MAILER ERROR] : {$mail->ErrorInfo}}";
                exit;
            }
        }
    }

    public static function sendCancelMail(string $contact_email) 
    {
        try {

            try {
                $mail = new PHPMailer(true);
                //$mail->SMTPDebug = 2;

            }catch (Exception $e) {        
                if (DEBUG) {
                    echo "[MAILER ERROR1] : {$mail->ErrorInfo}}";
                    exit;
                }
            }            
            
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';                                 //Pièce jointes ? A TESTER
            $mail->isSMTP();
            
            $mail->Host = 'smtp.gmail.com';                                    //Adresse IP ou DNS du serveur SMTP
            $mail->Port = '587';                                    //Port TCP du serveur SMTP
            $mail->SMTPAuth = true;                                     //Utiliser l'identification

            if($mail->SMTPAuth){
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;     //Protocole de sécurisation des échanges avec le SMTP ('tls'->plus de compatibilité) / 'ssl'->plus secure)
                $mail->Username = SMTP_USER;
                $mail->Password = SMTP_PASS;
            }

            $mail->From = SMTP_FROM;                                    //L'email à afficher pour l'envoi (adresse email servira de référence pour répondre)
            $mail->FromName = SMTP_FROM_NAME;                           //L'alias à afficher pour l'envoi (optionnel)              

            $mail->addAddress($contact_email);

            $mail->isHTML(true);
            $mail->Subject = 'Ecoride - Annulation covoiturage';
            $mail->Body = "
                <h2>Un covoiturage à été annulé</h2>
                <p>Un covoiturage auquel vous étiez inscrit à été annulé par le conducteur. Vos crédits vous ont été remboursé. Connectez-vous sur votre compte pour en savoir plus.</p>
            ";
            $mail->AltBody = "Un covoiturage auquel vous étiez inscrit à été annulé par le conducteur. Vos crédits vous ont été remboursé. Connectez-vous sur votre compte pour en savoir plus.";

            $mail->send();

            return true;

        } catch (Exception $e) {        
            if (DEBUG) {                
                echo "[MAILER ERROR] : {$mail->ErrorInfo}}";
                exit;
            }
        }
    }

}