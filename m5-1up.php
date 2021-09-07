<!DOCTYPE html>
    <html lang="ja">
        <head>
            <meta charset="UTF8">
            <title>mission_5-1</title>
        </head>
        <body>

        <?php
            $dsn = 'データベース名';
            $user = 'ユーザー名';
            $password = 'パスワード';
            $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
            $sql = "CREATE TABLE IF NOT EXISTS tbtest"
            ." ("
            . "id INT AUTO_INCREMENT PRIMARY KEY,"
            . "name char(32),"
            . "comment TEXT,"
            . "date TEXT,"
            . "password char(32)"
            .");";
            $stmt = $pdo->query($sql);

        if(!empty($_POST["edit"]) && !empty($_POST["Epass"])){//編集対象番号が送信された時
            $Epass=$_POST["Epass"];
            $edit=$_POST["edit"];
            $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                if($row["password"]==$Epass){
                    if($edit==$row["id"]){
                        $editnum=$row["id"];
                        $editname=$row["name"];
                        $editcomment=$row["comment"];
                    }
                }
            }

        }elseif(!empty($_POST["delete"]) && !empty($_POST["Dpass"])){//削除対象番号が送信された時
            $id = $_POST["delete"];
            $Dpass = $_POST["Dpass"];
            $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                if($row["password"]==$Dpass){
                    $sql = 'delete from tbtest where id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
        }elseif(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["NCpass"]) && empty($_POST["mark"])){//新規
                $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                $sql -> bindParam(':password', $password, PDO::PARAM_STR);
                $name = $_POST["name"];
                $comment = $_POST["comment"]; 
                $date=date("Y年m月d日　H:i:s");
                $password = $_POST["NCpass"];
                $sql -> execute();
                
                
        }elseif(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["NCpass"]) && !empty($_POST["mark"])){//編集
            $NCpass = $_POST["NCpass"];
            $mark = $_POST["mark"];
            $sql = 'SELECT * FROM tbtest';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                if($row["password"]==$NCpass){
                    $id = $mark; //変更する投稿番号
                    $name = $_POST["name"];
                    $comment = $_POST["comment"];
                    $date = date("Y年m月d日 H:i:s");
                    $sql = 'UPDATE tbtest SET name=:name,comment=:comment,date=:date WHERE id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                    $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
        }

            
        ?>
        <form action="" method="post">
            <input type="text" name="name" placeholder="名前" value="<?php if(!empty($edit) && $row["password"]==$Epass){echo $editname;}?>"><br>
            <input type="text" name="comment" placeholder="コメント" value="<?php if(!empty($edit) && $row["password"]==$Epass){echo $editcomment;}?>"><br>
            <input type="password" name="NCpass" placeholder="パスワード">
            <input type="submit" value="送信"><br>
            <input type="hidden" name="mark" value="<?php if(!empty($edit) && $row["password"]==$Epass){echo $editnum;}?>"><br>
            <input type="password" name="Dpass" placeholder="パスワード"><br>
            <input type="number" name="delete" placeholder="削除対象番号">
            <input type="submit" value="削除"><br>
            <p>
            <input type="password" name="Epass" placeholder="パスワード"><br>    
            <input type="number" name="edit" placeholder="編集対象番号">
            <input type="submit" value="編集">
            </p>
        </form>
        <?php    
            if((!empty($_POST["name"]) && !empty($_POST["comment"])) or !empty($_POST["delete"]) or !empty($_POST["edit"])){
                $sql = 'SELECT * FROM tbtest';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].' ';
                    echo $row['name'].' ';
                    echo $row['comment'].' ';
                    echo $row['date'].'<br>';
                echo "<hr>";
                }
            }

        ?>
        </body>