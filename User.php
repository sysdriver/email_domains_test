<?php

class User
{
    protected $id;
    public $name;
    public $gender; //32
    public $email;  //1024
    private static $db;

    public static function getTable()
    {
        return 'users';
    }

    public static function setDb($db)
    {
        self::$db = $db;
    }

    public static function bulkSave(array $users)
    {
        $query = "INSERT INTO `" . self::getTable() . "` (`name`,`gender`,`email`) VALUES ";
        foreach ($users as $k => $user) {
            $query .= "('" . $user->name . "', " . $user->gender . ", '" . $user->email ."'),";
        }
        $query = substr($query, 0, -1).';';

        try {
            $stmt = self::$db->query($query);
            $stmt->execute();
            echo self::$db->stmt->rowCount() . " records successfully saved into " .  self::getTable() . PHP_EOL;
        } catch (\Exception $e) {
            print "Error: " . $e->getMessage() . "<br/>";
        }
        //print_r($query);
    }

    public function __construct($name, $gender, $email)
    {
        if (!empty($name) && !empty($email) && !empty($gender)) {
            if (strpos($email, ',') !== false) {
                $mailArr = explode(',', $email);
            } else {
                $mailArr = [$email];
            }

            //validate emails
            foreach ($mailArr as $key => $mail) {
                if (!checkEmail($mail)) {
                    unset($mailArr[$key]);
                }
            }

            if (count($mailArr) > 0) {
                $this->name = substr((string) $name, 0, 31);
                $this->gender = (int) $gender;
                $this->email = substr(implode(',', $mailArr), 0, 1023);
            } else {
                throw new Exception("Can't create " . self::class . " class instance: no valid emails");
            }
        } else {
            throw new Exception("Can't create " . self::class . " class instance: some fields are empty");
        }
    }

    public function save()
    {
        if (empty(self::$db)) {
            throw new \Exception("Error: empty \$db property", 1);
        }

        $query = "INSERT INTO `" . self::getTable() . "` (`name`,`gender`,`email`)"
        . " VALUES ('" . $this->name . "', " . $this->gender . ", '" . $this->email ."');";

        try {
            $stmt = self::$db->query($query);
            $stmt->execute();
            echo "Record successfully saved: " .  self::$db->lastInsertId();
        } catch (\Exception $e) {
            print "Error: " . $e->getMessage() . "<br/>";
        }
    }
}
