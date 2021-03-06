<?php


namespace FriendlySold\DAO;


use FriendlySold\Domain\User;


class UserDAO extends DAO 
{
	public function findAll(){
		$db= "SELECT * FROM t_user order by usr_id_groupe";
		$result =  $this->getDb()->fetchAll($db);
		//Convertir en tableau, l'objet que l'on recupère de la base de donnée
		$tableau_db=array();
		foreach ($result as $row) {
			$id = $row['usr_id'];
			$tableau_db[$id] = $this->buildDomainObject($row);
		}
		return $tableau_db;
	}


    public function findByGroup($group){
        $db="SELECT * FROM t_user WHERE usr_id_groupe='$group'";
        $result = $this->getDb()->fetchAll($db);

        $tableau_db=array();
        foreach ($result as $row) {
            $id = $row['usr_id_groupe'];
            $di = $row['usr_name'];
            $tableau_db[$id+$di] = $this -> buildDomainObject($row);
        }
        return $tableau_db;
    }

    
            public function find($id) {
               if ($id == null)
            throw new \Exception("id null ");
                else {
            $sql = "SELECT * FROM t_user WHERE usr_id=:id";
            $dbh = $this->getDb()->prepare($sql);
            $dbh->execute(array('id'=>$id));
            $result = $dbh->fetchAll();
            if (count($result)>0){
                $usr = $this->buildDomainObject($result[0]);
                return $usr;
            }
            
            else
                throw new \Exception("No user matching id " . $id);
        }

    }

    public function getColorName($id){
        $db = "SELECT usr_couleur FROM t_user WHERE usr_id= $id";
      $result = $this->getDb()->fetchAll($db);
                return $result;

                
        }

        
    


    public function save(User $user){
               
        // if my id exist, I update my User table
        if (!is_null($user->getId())){
      
            echo "update:\n";
            $update = "UPDATE t_user SET usr_name=:username,usr_id_groupe=:usergroup,usr_couleur=:usercolor WHERE usr_id=:userid";
            $query = $this->getDb()->prepare($update);

            $query->bindValue(':userid', $user->getId());
            $query->bindValue(':username', $user->getUsername());
            $query->bindValue(':usergroup', $user->getGroup());
            $query->bindValue(':usercolor', $user->getColor());
          
            $query->execute();

            if($query->errorCode() != "00000");
                var_dump($query->errorInfo());
            return $user;
        }

        // If not, I create a new user
         else { 
            echo "create:\n";
            $create = "INSERT INTO t_user(usr_name, usr_id_groupe, usr_couleur) VALUES (:username,:usergroup,:usercolor)";
            $query = $this->getDb()->prepare($create);

            $query->bindValue(':username', $user->getUsername());
            $query->bindValue(':usergroup', $user->getGroup());
            $query->bindValue(':usercolor', $user->getColor());
            
            $query->execute();

            $user->setId($this->getDb()->lastInsertId());
            var_dump($user);
            return $user;
        } 
  
    }

	/*public function delete($id){
	
	$db = "DELETE FROM `t_user` WHERE `usr_id` = $id";
	
	}*/
	
	public function delete($id){
		if ($this->find($id))
			$this->getDb()->delete('t_user', array('usr_id' => $id));
	}


   /**

     * Creates a User object based on a DB row.

     *

     * @param array $row The DB row containing User data.

     * @return \FriendlySold\Domain\User

     */

    protected function buildDomainObject($row) {

        $user= new User();

        $user->setId($row['usr_id']);

        $user->setUsername($row['usr_name']);

        $user->setGroup($row['usr_id_groupe']);

        $user->setColor($row['usr_couleur']);

        return $user;

    }

    public function toJSONStructure(Array $user){
        $jsonResult=[];
        foreach ($user as $key => $user) {
            $jsonResult[$key] = [];
            $jsonResult[$key]["Id"] = $user->getId();
            $jsonResult[$key]["name"] = $user->getUsername();
            $jsonResult[$key]["groupe"] = $user->getGroup();
            $jsonResult[$key]["couleur"] = $user->getColor();
        }


        return $jsonResult;
                
    }
}
