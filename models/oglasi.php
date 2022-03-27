<?php class Oglas{
	public $id;
	public $title;
	public $description;
	public $user_id;
	public $images;
	public $show_image;
	public $postdate;
	public $enddate;
	public $categories_ids;
	public $views;

	public function __construct( $id, $title, $description, $user_id, $images, $show_image, $postdate, $enddate, $categories_ids, $views ){
		$this->id = $id;
		$this->title = $title;
		$this->description = $description;
		$this->user_id = $user_id;
		$this->images = $images;
		$this->show_image = $show_image;
		$this->postdate = $postdate;
		$this->enddate = $enddate;
		$this->categories_ids = $categories_ids;
		$this->views = $views;
	}

	public static function vsi(){
		$list = [];
		$db = Db::getInstance();
		$result = mysqli_query( $db, "SELECT * FROM ads" );

		while( $row = mysqli_fetch_assoc( $result ) )
			$list[] = new Oglas( $row["id"], $row["title"], $row["description"], $row["user_id"], $row["images"], $row["show_image"],
				$row["postdate"], $row["enddate"], $row["categories_ids"], $row["views"] );
    
		return $list;
	}

	public static function najdi( $id ){
		$id = intval( $id );
		
		$db = Db::getInstance();
		$result = mysqli_query( $db, "SELECT * FROM ads where id=$id" );
		$row = mysqli_fetch_assoc( $result );
		
		return new Oglas( $row["id"], $row["title"], $row["description"], $row["user_id"], $row["images"], $row["show_image"],
			$row["postdate"], $row["enddate"], $row["categories_ids"], $row["views"] );
	}
	
	private static function reArrayFiles( $file_post ){
		$file_ary = array();
		for( $i=0; $i<count( $file_post["name"] ); $i++ )
			foreach( array_keys( $file_post ) as $key )
				$file_ary[$i][$key] = $file_post[$key][$i];

		return $file_ary;
	}

	public static function dodaj( $title, $description, $user_id, $show, $images, $categories_ids ){
		$db = Db::getInstance();
		
		if( $stmt = mysqli_prepare( $db, "INSERT into ads ( title, description, user_id, images, show_image, postdate, enddate, categories_ids, views )
			Values ( ?, ?, ?, ?, ?, now(), DATE_ADD( now(), INTERVAL 30 DAY ), ?, 0 )" ) ){
			$ts = new DateTime( "now", new DateTimeZone( "Europe/Ljubljana" ) );
			$ts->setTimestamp( time() );
			$postdate = $ts->format( 'Y-m-d H:i:s' );
			
			$pot = "./slike/" . $user_id . "/" . $ts->format( 'Y-m-d_H-i-s' ) . "/";
			mkdir( $pot, 0777, true );
			
			$show_pic = $show["name"];
			$imgs = self::reArrayFiles( $images );
			$allowed = array( "image/jpeg", "image/gif", "image/png" );
			
			move_uploaded_file( $show["tmp_name"], $pot . $show_pic );
			foreach( $imgs as $img ){
				if( $img["name"] != $show_pic && $img["error"] == 0 ){
					if( !in_array( $img["type"], $allowed ) ){
						$files = glob( $pot . "*" );
						foreach( $files as $file ){
							unlink( $file );
						}
						rmdir( $pot );
						return false;
					}
					move_uploaded_file( $img["tmp_name"], $pot . $img["name"] );
				}
			}
			
			mysqli_stmt_bind_param( $stmt, "ssisss", $title, $description, $user_id, $pot, $show_pic, $categories_ids );
			mysqli_stmt_execute( $stmt );
			mysqli_stmt_close( $stmt );
		}else
			return false;
	   
		$id = mysqli_insert_id( $db );
		
		return Oglas::najdi( $id );
	}
	
	public static function kategorije(){
		$db = Db::getInstance();
		$result = mysqli_query( $db, "SELECT * FROM categories" );
		
		$categories = array();
		while( $row = mysqli_fetch_assoc( $result ) )
			array_push( $categories, $row );
		
		return $categories;
	}
	
	private static function get_subCategories( $category ){
		$db = Db::getInstance();
		$result = mysqli_query( $db, "SELECT * FROM categories WHERE id=$category" );
		$row = mysqli_fetch_assoc( $result );
		
		$sub_cats = array();
		$subs = $row["sub_categories"];
		if( !empty( $subs ) ){
			$ids = explode( ' ', $subs );
			foreach( $ids as $id )
				array_push( $sub_cats, $id );
		}
		
		return $sub_cats;
	}
	
	private static function getCategory( $category ){
		$db = Db::getInstance();
		$result = mysqli_query( $db, "SELECT * FROM categories WHERE id=$category" );
		
		return mysqli_fetch_assoc( $result );
	}
	
	public static function get_SubCats( $cat ){
		$subCats = array();
		foreach( self::get_subCategories( $cat ) as $category ){
			array_push( $subCats, self::getCategory( $category ) );
			self::get_SubCats( $category );
		}
		
		return $subCats;
	}
	
	public static function arrayFromString( $string ){
		$sub_cats = array();
		if( !empty( $string ) ){
			$ids = explode( ' ', $string );
			foreach( $ids as $id )
				array_push( $sub_cats, $id );
		}
		
		return $sub_cats;
	}
	
	public function uredi( $id, $title, $description, $files ){
		$db = Db::getInstance();
		
		/*if( $files["show"]["error"] == 0 )
			$query = "UPDATE ads SET title='$title', description='$description', show_image='$show', categories_ids='$cats' WHERE id='$id';";
		$query = "UPDATE ads SET title='$title', description='$description', categories_ids = '$cats' WHERE id='$id';";*/
	}
} ?>