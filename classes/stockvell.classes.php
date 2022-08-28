<?php
class StockvellController extends Database
{
    // PSC = pending stockvell pack 
    public function getPSC($status)
    {
        // All stockvell of a member
        $sql = "SELECT * FROM stockvells WHERE status=:status";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam('status', $status);
        if ($stmt->execute()) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        return [];
    }

    public function updateStockvell($stockvell_id, $input_list)
    {
        $cols = array();
        // Remove blank inputs and password2
        foreach ($input_list as $key => $val) {
            if (!empty($val) && $key !== "password2")   $cols[] = "$key = '$val'";
        }
        $updateElement = implode(', ', $cols);


        /// Update element 
        $sql = "UPDATE stockvells SET $updateElement WHERE id=:stockvell_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam('stockvell_id', $stockvell_id);

        if (!$stmt->execute()) {
            header('Location: /admin.php?error=stmtfailed');
            exit();
        }

        header('Location: /admin.php?error=none');
    }
}
