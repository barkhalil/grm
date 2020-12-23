<?php
/**
 * Created by PhpStorm.
 * User: nagui
 * Date: 13/12/17
 * Time: 16:46
 */

class SendMail {
    public $mailer;
    public function __construct(PHPMailer $mailer) {
        $this->mailer=$mailer;
        //Server settings
        $this->mailer->SMTPDebug = 0;                                 // Enable verbose debug output
        $this->mailer->isSMTP();                                      // Set mailer to use SMTP
        $this->mailer->Host = 'smtp.topnet.tn';  // Specify main and backup SMTP servers                                      // Enable TLS encryption, `ssl` also accepted
        $this->mailer->Port = 25;
        $this->mailer->CharSet = 'UTF-8';
        $this->mailer->ContentType = "text/html";
        $this->mailer->isHTML(true);  // Set email format to HTML
    }
    public function sendLinkDoc($links,$titre,$users) {
        try {
            //Recipients
            $this->mailer->setFrom('notification@vital.com.tn', 'YaTranfert');
            foreach ($users as $user){
                $this->mailer->addAddress( $user['Email'],$user['Nom'].' '.$user['Prenom']);     // Add a recipient
            }
            $this->mailer->addBCC('vital.naot@gmail.com');
            $this->mailer->addReplyTo('notification@vital.com.tn', 'YaTranfert');
            //$this->mailer->addBCC('not@vital.com.tn');// Passing `true` enables exceptions
            //$this->mailer->addBCC('vital.naot@gmail.com');*/// Passing `true` enables exceptions
            //Content
            $HtmlTable = "
                          <h3>Nouveau document ajouté sur CRM</h3>
                          <p>
                          Un nouveau document a été ajouté dans la liste des documents partagés sur CRM<br/>
                          voici un lien pour le télécharger directement : <br/>
                          <a href='http://www.vital-crm.tn:10/$links' target='_blank'>$titre</a>
                         
</p>
                          
                          
                          ";
            $this->mailer->msgHTML("$HtmlTable");
            $this->mailer->Subject = $titre;
            $this->mailer->Body = "$HtmlTable <br/> Ceci est un mail automatique merci de ne pas répondre à ce mail";
            $this->mailer->AltBody = '';
            if($this->mailer->send()){
                return true;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
    public function getAdmins(){
        global $PDO;
        $strSQL = "SELECT * FROM users WHERE  active = 1 and (type<=2 or type >5)";
        $query = $PDO->prepare($strSQL);
        $query->execute();
        $retour = $query->fetchAll(PDO::FETCH_ASSOC);
        return $retour;
    }
    public function sendRecouvrementMail($idPros,$emailPros,$date_echeance,$type_recouvr,$num_reglem,$deleg,$montant) {
        try {
            $now=date('d/m/y');
            $prospect=get('*','prospect',array('id='=>$idPros));
            $delegue=get('*','users',array('id='=>$deleg));
            //Recipients
            $this->mailer->setFrom('notification@vital.com.tn', 'Recouvrement');
            $this->mailer->addAddress( $emailPros,$prospect['nom'].' '.$prospect['prenom']);     // Add a recipient
            //->mailer->addAddress( $delegue['Email'],$delegue['Nom'].' '.$delegue['Prenom']);     // Add a recipient
            //$this->mailer->addBCC('vital.naot@gmail.com');
            //->mailer->addReplyTo('notification@vital.com.tn', 'Recouvrement');
            //Content
            $mnt=' de '.$montant;
            if($date_echeance!='') {
                $msg="Avec l'échéance ".$date_echeance;
            }
            if($type_recouvr==5) {
                $mnt="";
                $typeRecouv='Promesse';

            }
            if($type_recouvr==3) {
                $msg.='N° chéque '.$num_reglem;
                $typeRecouv='Chéque';
            }
            if($type_recouvr==4) {
                $msg.='N° traite '.$num_reglem;
                $typeRecouv='Traite bancaire';
            }
            if($type_recouvr==1) {
                $typeRecouv='Espèce';
            }
            $HtmlTable = "
                          <h3>Reception d'un reglement</h3>
                          <p>Nous avons bien accusé la reception d'un reglement".$mnt." sous forme de ".$typeRecouv."</p>
                          <p>".$msg." par notre délégué ".$deleg." à la date de ".$now."</p>
                <small>Nous vous remercions pour votre confiance</small><br>
                <footer>
                    <p>Suivi recouvrement</p><br/>
                    <a href=\"http://vital.com.tn/\" target=\"_blank\">Laboratoir VITAL</a><br/>
                    <span>Téléphone: (+216) 71 386 016 </span><span>Fax : (+216) 79 396 081 </span><br/>
                    <span>Email: contact@vital.com.tn</span><br/>
                    <span>Z.I Ben Arous – Route Mornag – Ben Arous </span><br/>
                </footer>";
            $this->mailer->msgHTML("$HtmlTable");
            $this->mailer->Subject = 'Reception d\'un reglement';
            $this->mailer->Body = "$HtmlTable <br/> Ceci est un mail automatiquec Merci de ne pas rérondre .";
            $this->mailer->AltBody = '';
            if($this->mailer->send()){
                return true;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
    public function send($to, $body)
    {
        try {
            //$HtmlTable = ' Bonjour ,  <br> j ai une probleme au niveau de mon pc portable et macintosh  je ne pas trouver ni le macintosh ni le souris ni le clavier <br> merci ';

            $this->mailer->msgHTML("$body");
            //$this->mailer->Subject = 'Nouvelles Tâches';

            $this->mailer->Body = $body;

            $this->mailer->AltBody = "";

//Set who the message is to be sent from
            $this->mailer->setFrom('CRM@vital.com.tn', 'CRM ');


            $c = 0;
            $this->mailer->addAddress("$to");
            // $this->mailer->send();

            if ($this->mailer->send()) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
    public function send2($to, $body,$obj,$cc)
    {
        try {
            //$HtmlTable = ' Bonjour ,  <br> j ai une probleme au niveau de mon pc portable et macintosh  je ne pas trouver ni le macintosh ni le souris ni le clavier <br> merci ';

            $this->mailer->msgHTML("$body");
            //$this->mailer->Subject = 'Nouvelles Tâches';

            $this->mailer->Body = $body;

            $this->mailer->AltBody = "";

//Set who the message is to be sent from
            $this->mailer->setFrom('CRM@vital.com.tn', 'CRM ');
            $this->mailer->addBCC($cc,' ');
            $this->mailer->Subject=$obj;


            $c = 0;
            $this->mailer->addAddress("$to");
            // $this->mailer->send();

            if ($this->mailer->send()) {
                return true;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
}
