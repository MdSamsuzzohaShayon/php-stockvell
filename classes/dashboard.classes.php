<?php 
class DashboardController extends Database{
    private $member_email;
    public function __construct($member_email){
       $this->member_email = $member_email;
    }

    public function getCurrentMember(){
        $sql = "SELECT id, firstname, surname, email, country, phone, gender, profession, interest, govt_id, source, role FROM members WHERE email=:email";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam('email', $this->member_email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        // var_dump($result);
        return $result;
    }
}
?>