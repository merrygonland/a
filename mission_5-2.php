<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <title>***</title>
     </head>
  <body>
	<?php
    	//mysqlに接続
   	$dsn = '******';
	$user = '******';
	$password = '*******';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));	
	
       	//テーブルを作成
    	$sql = "CREATE TABLE IF NOT EXISTS tb0"
  	." ("
   	. "id INT AUTO_INCREMENT PRIMARY KEY,"
   	. "name char(32),"
   	. "comment TEXT,"
    	. "date_time TEXT,"
    	. "pass TEXT"
   	.");";
   	$stmt = $pdo->query($sql);
   	
	//投稿機能　
	//フォーム内が空でない場合に以下を実行
    	if (!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"])&&empty($_POST["editNO"])) {
    		
    		//入力データの受け取りを変数に代入
    		$name = $_POST["name"];
    		$comment = $_POST["comment"];
   		$date_time = date("Y/m/d H:i:s");
   		$pass = $_POST["pass"];
   		
   		//ユーザーからの入力受付
   		$sql = $pdo -> prepare("INSERT INTO tb0 (name, comment, date_time, pass) VALUES (:name, :comment, :date_time, :pass)");
   		
   		//SQLの一部を変数化
    		$sql -> bindParam(':name', $name, PDO::PARAM_STR);
    		$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
   		$sql -> bindParam(':date_time', $date_time, PDO::PARAM_STR);
    		$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
    		
    		//実行
    		$sql -> execute();
  	}
  
  	//編集選択機能
  	//編集投稿番号とパスワードが空白じゃない場合
  	if(!empty($_POST["enum"]) && !empty($_POST["editpass"])){
       		$sql =$pdo -> prepare('SELECT * FROM tb0 WHERE id=:id AND pass=:pass');
       		$sql -> bindParam(':id', $id, PDO::PARAM_INT);
       		$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
       		$id = $_POST["enum"];
       		$pass = $_POST["editpass"];
       		$sql -> execute();
  	   	$results = $sql->fetchAll();
       		foreach($results as $row){
  	   	$edit_name = $row['name'];
       		$edit_comment = $row['comment'];
       		$edit_num=$row['id'];
     		}	
   	}
   	
	//削除機能
	//削除番号とパスワードフォームが空白じゃない場合
    	if(!empty($_POST["dnum"]) && !empty($_POST["delpass"])){
    		$id = $_POST["dnum"];
    		$pass = $_POST["delpass"];
    		$sql = 'delete from tb0 where id=:id and pass=:pass';
    		$stmt = $pdo->prepare($sql);
    		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
    		$stmt->bindParam(':pass', $pass, PDO::PARAM_INT);
    		$stmt->execute();
    	}
    	
  	//編集実行機能
  	//編集投稿番号(hidden)が空白じゃない場合
  	if(!empty($_POST["editNO"])){
    		$id = $_POST["editNO"]; 
    		$pass = $_POST["pass"];
    		$edit_name = $_POST["name"];
    		$edit_comment = $_POST["comment"]; 
    		$sql = 'update tb0 set name=:name,comment=:comment where id=:id and pass=:pass';
    		$sql = $pdo->prepare($sql);
    		$sql->bindParam(':name', $edit_name, PDO::PARAM_STR);
    		$sql->bindParam(':comment', $edit_comment, PDO::PARAM_STR);
    		$sql->bindParam(':id', $id, PDO::PARAM_INT);
    		$sql->bindParam(':pass', $pass, PDO::PARAM_STR);
    		$sql->execute();
  	}
    ?>
    
 

    <form action="" method="post">
    <input type="text" name="name" placeholder="名前" value="<?php if(isset($edit_name)) {echo $edit_name;} ?>"><br>
    <input type="text" name="comment" placeholder="コメント" value="<?php if(isset($edit_comment)) {echo $edit_comment;} ?>"><br>
    <input type="hidden" name="editNO" value="<?php if(isset($edit_num)) {echo $edit_num;} ?>">
    <input type = "text" name = "pass" placeholder="パスワード"><br>
    <input type="submit" name="submit" value="送信"></br>
    </br>
    </form>

    <form action="" method="post">
    <input type="text" name="dnum" placeholder="削除対象番号"><br>
    <input type = "text" name = "delpass" placeholder="パスワード"><br>
    <input type="submit" name="delete" value="削除"></br>
    </br>
    </form>

    <form action="" method="post">
    <input type="text" name="enum" placeholder="編集対象番号" ><br>
    <input type = "text" name = "editpass" placeholder="パスワード"><br>
     <input type="submit" value="編集">
    </form>
    <form>
      <p>------------------------------------------------</p><br>
    </form>
    <?php
    
    	//一覧を表示
    	$sql = 'SELECT * FROM tb0';
    	$stmt = $pdo->query($sql);
    	$results = $stmt->fetchAll();
    	foreach ($results as $row){
      		//$rowの中にはテーブルのカラム名が入る
      		echo $row['id'].' ';
      		echo $row['name'].' ';
      		echo $row['comment'].' ';
      		echo $row['date_time'].'<br>';
      		echo "<hr>";
    		}
    ?>

  </body>
</html>