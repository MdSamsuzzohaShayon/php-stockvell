<?php 
namespace Models\Member;

use Models\Member\Member;


class FetchMember extends Member{
public function __construct(){}

public function findMemberByID($member_id, $redirect_url)
{
    $stmt = $this->connect()->prepare("SELECT id, firstname, surname, email  FROM members WHERE id=:member_id;");

    if (!$stmt->execute(array("member_id"=> $member_id))) {
        // $stmt = null;
        // header("Location: /$redirect_url?error=stmtfailed");
        // exit();
        return false;
    }
    $member_found = $stmt->fetch(\PDO::FETCH_OBJ);
    return $member_found;
}
}
