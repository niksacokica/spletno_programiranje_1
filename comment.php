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
		
		echo "<script>console.log('we good');</script>";
		
		$this->id = mysqli_insert_id( $db );
	}
} ?>