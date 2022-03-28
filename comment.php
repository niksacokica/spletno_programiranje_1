<?php class Comment{
	public $id;
	public $ad_id;
	public $email;
	public $username;
	public $content;
	public $postdate;
	public $ip;

	function __construct( $ad_id, $email, $username, $content, $postdate, $ip, $id = 0 ){
		$this->ad_id = $ad_id;
		$this->email = $email;
		$this->username = $username;
		$this->content = $content;
		$this->postdate = $postdate;
		$this->ip = $ip;
		$this->id = $id;
	}
	
	public function dodaj( $db ){
		$ad_id = $this->ad_id;
		$email = $this->email;
		$username = $this->username;
		$content = $this->content;
		$postdate = $this->postdate;
		$ip = $this->ip;
		$result = mysqli_query( $db, "insert into comments ( ad_id, email, username, content, postdate, ip ) values
			( '$ad_id', '$email', '$username', '$content', '$postdate', '$ip' );" );

		if( mysqli_error( $db ) ){
			var_dump( mysqli_error( $db ) );
			exit();
		}
		
		$this->id = mysqli_insert_id( $db );
	}
	
	public static function vrniEnega( $db, $ad_id, $id ){
		$result = mysqli_query( $db, "Select * from comments where id='$id' and ad_id='$ad_id'" );

		if( mysqli_error( $db ) ){
			var_dump( mysqli_error( $db ) );
			exit();
		}
		
		$row = mysqli_fetch_assoc( $result );
		$comment = new Comment( $row["ad_id"], $row["email"], $row["username"], $row["content"], $row["postdate"], $row["ip"], $row["id"] );
	
		return $comment;
	}
	
	private static function cmp( $a, $b ){
		return $a->postdate < $b->postdate;
	}
	
	public static function vrniVsePost( $db, $id ){
		$result = mysqli_query( $db, "Select * from comments where ad_id='$id'" );

		if( mysqli_error( $db ) ){
			var_dump( mysqli_error( $db ) );
			exit();
		}
		
		$comments=array();
		while( $row = mysqli_fetch_assoc( $result ) )
			array_push( $comments, new Comment( $row["ad_id"], $row["email"], $row["username"], $row["content"], $row["postdate"], $row["ip"], $row["id"] ) );
		
		usort( $comments, "Comment::cmp" );
		
		return $comments;
	}
	
	public static function vrniVse( $db ){
		$result = mysqli_query( $db, "Select * from comments" );

		if( mysqli_error( $db ) ) {
			var_dump( mysqli_error( $db ) );
			exit();
		}
		
		$comments=array();
		while( $row = mysqli_fetch_assoc( $result ) )
			array_push( $comments, new Comment( $row["ad_id"], $row["email"], $row["username"], $row["content"], $row["postdate"], $row["ip"], $row["id"] ) );
		
		usort( $comments, "Comment::cmp" );
		
		return $comments;
	}
	
	public static function del( $db, $id, $user ){
		$check = mysqli_query( $db, "Select * from comments where id='$id'" );
		if( mysqli_error( $db ) ) {
			var_dump( mysqli_error( $db ) );
			exit();
		}
		$row = mysqli_fetch_assoc( $result );
		$ad_id = $row["ad_id"];
		
		$check2 = mysqli_query( $db, "Select * from ads where id='$ad_id'" );
		if( mysqli_error( $db ) ) {
			var_dump( mysqli_error( $db ) );
			exit();
		}
		$row2 = mysqli_fetch_assoc( $result );
		$user_id = $row2["user_id"];
		
		if( $user_id == $user ){
			$result = mysqli_query( $db, "DELETE FROM comments WHERE id='$id'" );

			if( mysqli_error( $db ) ) {
				var_dump( mysqli_error( $db ) );
				exit();
			}
			
			return "komentar je bil uspešno izbrisan";
		}
	}
	
	public static function vrniPet( $db ){
		$result = mysqli_query( $db, "SELECT * FROM comments ORDER BY comments.postdate DESC LIMIT 5" );

		if( mysqli_error( $db ) ){
			var_dump( mysqli_error( $db ) );
			exit();
		}
		
		$comments=array();
		while( $row = mysqli_fetch_assoc( $result ) )
			array_push( $comments, new Comment( $row["ad_id"], $row["email"], $row["username"], $row["content"], $row["postdate"], $row["ip"], $row["id"] ) );
		
		usort( $comments, "Comment::cmp" );
	
		return $comments;
	}
} ?>