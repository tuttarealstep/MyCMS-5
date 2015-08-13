<?php
/*                     *\
|	MYCMS - TProgram    |
\*                     */
class MY_Blog {
		
		function __construct() {
		}
		
	public static
		function get($oggetto, $id){
			global $my_db;
				if(empty($id)){
					//
					return NULL;
				}
				if(empty($oggetto)){
					//NON FACCIO NIENTE
					return NULL;
				}
				else
				{
					switch ($oggetto)
					{
					 case 'id':
					 	$informazione = $my_db->single("SELECT postID FROM my_blog WHERE postID = :blog_id LIMIT 1", array('blog_id'=>$id));
						echo $informazione;
					 break;
					 case 'title':
					 	$informazione = $my_db->single("SELECT postTITLE FROM my_blog WHERE postID = :blog_id LIMIT 1", array('blog_id'=>$id));
						echo htmlspecialchars($informazione);
					 break;
					 case 'content':
					 	$informazione = $my_db->single("SELECT postCONT FROM my_blog WHERE postID = :blog_id LIMIT 1", array('blog_id'=>$id));
						echo $informazione;
					 break;
					 case 'date':
					 	$informazione = $my_db->single("SELECT postDATE FROM my_blog WHERE postID = :blog_id LIMIT 1", array('blog_id'=>$id));
						echo $informazione;
					 break;
					 case 'author':
					 	$informazione = $my_db->single("SELECT postAUTHOR FROM my_blog WHERE postID = :blog_id LIMIT 1", array('blog_id'=>$id));
						echo remove_space($informazione);
					 break;
					 case 'authorspace':
					 	$informazione = $my_db->single("SELECT postAUTHOR FROM my_blog WHERE postID = :blog_id LIMIT 1", array('blog_id'=>$id));
						echo $informazione;
					 break;
					 case 'category':
					 	$informazione = $my_db->single("SELECT postCATEGORY FROM my_blog WHERE postID = :blog_id LIMIT 1", array('blog_id'=>$id));
						echo $informazione;
					 break;
					 case 'permalink':
					 	$informazione = $my_db->single("SELECT postPERMALINK FROM my_blog WHERE postID = :blog_id LIMIT 1", array('blog_id'=>$id));
						echo $informazione;
					 break;
					 case 'idFROMpermalink':
					 	$informazione = $my_db->single("SELECT postID FROM my_blog WHERE postPERMALINK = :perm_id LIMIT 1", array('perm_id'=>$id));
						echo $informazione;
					 break;
					}
				}
    }
	public static
		function gets($oggetto, $id){
			global $my_db;
				if(empty($id)){
					//NON FACCIO NIENTE
					return NULL;
				}
				if(empty($oggetto)){
					//NON FACCIO NIENTE
					return NULL;
				}
				else
				{
					switch ($oggetto)
					{
					 case 'id':
					 	$informazione = $my_db->single("SELECT postID FROM my_blog WHERE postID = :blog_id LIMIT 1", array('blog_id'=>$id));
						return $informazione;
					 break;
					 case 'title':
					 	$informazione = $my_db->single("SELECT postTITLE FROM my_blog WHERE postID = :blog_id LIMIT 1", array('blog_id'=>$id));
						return htmlspecialchars($informazione);
					 break;
					 case 'content':
					 	$informazione = $my_db->single("SELECT postCONT FROM my_blog WHERE postID = :blog_id LIMIT 1", array('blog_id'=>$id));
						return $informazione;
					 break;
					 case 'date':
					 	$informazione = $my_db->single("SELECT postDATE FROM my_blog WHERE postID = :blog_id LIMIT 1", array('blog_id'=>$id));
						return $informazione;
					 break;
					 case 'author':
					 	$informazione = $my_db->single("SELECT postAUTHOR FROM my_blog WHERE postID = :blog_id LIMIT 1", array('blog_id'=>$id));
						return remove_space($informazione);
					 break;
					 case 'authorspace':
					 	$informazione = $my_db->single("SELECT postAUTHOR FROM my_blog WHERE postID = :blog_id LIMIT 1", array('blog_id'=>$id));
						return $informazione;
					 break;
					 case 'category':
					 	$informazione = $my_db->single("SELECT postCATEGORY FROM my_blog WHERE postID = :blog_id LIMIT 1", array('blog_id'=>$id));
						return $informazione;
					 break;
					 case 'permalink':
					 	$informazione = $my_db->single("SELECT postPERMALINK FROM my_blog WHERE postID = :blog_id LIMIT 1", array('blog_id'=>$id));
						return $informazione;
					 break;
					 case 'idFROMpermalink':
					 	$informazione = $my_db->single("SELECT postID FROM my_blog WHERE postPERMALINK = :perm_id LIMIT 1", array('perm_id'=>$id));
						return $informazione;
					 break;
					}
				}
    }
	public static
		function permalinkfinder($permalink = NULL){
			global $my_db;
				if(!empty($permalink)){
					$sql = $my_db->iftrue("SELECT * FROM my_blog WHERE postPERMALINK = :permalink LIMIT 1", array('permalink'=>$permalink));			
					return $sql;
				} else {
					return NULL;
				}
			}
	public static
		function categoryfinder($category = NULL){
			global $my_db;
				if(!empty($category)){
					$sql = $my_db->iftrue("SELECT * FROM my_blog_category WHERE catNAME = :category LIMIT 1", array('category'=>$category));							
					return $sql;
				
				} else {
					return NULL;
				}
			}
	public static 
		function addcomments($postid, $commento){
			global $my_db;
			
			if(get_settings_value('blog_comments_active') == 'true'){
				$data = time_normal_full(time());
				$autore = $_SESSION['user']['id'];
				if(!empty($postid)){
					if(!empty($commento)){
						if(get_settings_value('blog_comments_approve') == 'false'){
							$sql = $my_db->query("INSERT INTO my_blog_post_comments (author,comments,postid,date,enable) VALUES(:autore, :commento, :postid, :data, '1')", array('autore'=>$autore,'commento'=>$commento,'postid'=>$postid,'data'=>$data));
						} else {
							$sql = $my_db->query("INSERT INTO my_blog_post_comments (author,comments,postid,date,enable) VALUES(:autore, :commento, :postid, :data,  '0')", array('autore'=>$autore,'commento'=>$commento,'postid'=>$postid,'data'=>$data));
						}
					}
				}
			}
				
			}
	public static 
		function set_private($bool){
				global $my_db;
				
				$sql = $my_db->single("SELECT * FROM my_menu WHERE menu_name = 'Blog' LIMIT 1");
				$information = $sql;
				
				if($bool == true){
					if($information){
						$my_db->query("UPDATE my_menu SET menu_enabled='0' WHERE menu_name='Blog'");	
						return true;
					} else {
						return false;	
					}
				} else {
					if($information){
						$my_db->query("UPDATE my_menu SET menu_enabled='1' WHERE menu_name='Blog'");	
						return true;
					} else {
						return false;	
					}
				}
				
		}
		
}

