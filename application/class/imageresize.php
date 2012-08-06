<?
class resize
{
	function thumbnail($filethumb,$file,$Twidth,$Theight,$cmd_parms,$tag, $command=IMAGEMAGICKS_COMMAND_PATH)
	{
		$src_image = $file;
		$dest_image = $filethumb;
		
		if(isset($command) && ($command != ''))
		{
			$cmd1 = IMAGEMAGICKS_COMMAND_PATH." ".$cmd_parms." -crop ".$Twidth."x".$Theight."+0+0 +repage ! ". $src_image." ". $dest_image;
			//unset($cmd_parms);
			$result1 = system($cmd1);
			
			if($result1) 
			{
				return true;
			}
		}
		else
		{
			return true;
		}
	}

	function IMG_RESIZE($name,$path,$acc_type)
	{
		$temp=$_FILES[$name]['tmp_name'];
		$img=$_FILES[$name]['name'];
		$type=substr($_FILES[$name]['type'],0,strrpos($_FILES[$name]['type'],"/"));
		//echo $path;
		//exit;
	
			if($type=="image" )
			{
			  $pos = strrpos($img, ".");
			  $len=strlen($img)-$pos;
			  $exe=substr($img,$pos,$len);
		    $uniq=md5($img.time().rand());
		    $img=$uniq.$exe;			  
		    $dir=$path.$img;
		    $tag = "width";
		    
		    if(strtolower($name) == "logo") 
				{
					$lrg_img_name = "large_".$uniq.$exe;	  
					$lrg_dir_name = $path.$lrg_img_name;
					$med_img_name = "medium_".$uniq.$exe;	  
					$med_dir_name = $path.$med_img_name;
					$small_img_name = "small_".$uniq.$exe;	  
					$small_dir_name = $path.$small_img_name;
					
					$lrg_width = 230; $lrg_height = 74; 
					$med_width = 180; $med_height = 230; 
					$small_width = 83; $small_height = 100; 
					
					$this->thumbnail($lrg_dir_name,$temp,$lrg_width,$lrg_height,$tag);
					$this->thumbnail($med_dir_name,$temp,$med_width,$med_height,$tag);
					$this->thumbnail($small_dir_name,$temp,$small_width,$small_height,$tag);	
				}
				elseif(strtolower($name) == "thumbnail") 
				{
					$dir_name = $path.$img;
					
					$small_width = 83; $small_height = 100; 
					$cmd_parms = "x100 -resize '200x<' -resize 30% -gravity center";
					$this->thumbnail($dir_name,$temp,$small_width,$small_height,$cmd_parms,$tag);	
				}
				else
				{			 	
				 	$lrg_img_name = "large_".$uniq.$exe;	  
					$lrg_dir_name = $path.$lrg_img_name;
					$med_img_name = "medium_".$uniq.$exe;	  
					$med_dir_name = $path.$med_img_name;
					$small_img_name = "small_".$uniq.$exe;	  
					$small_dir_name = $path.$small_img_name;
					$tiny_img_name = "tiny_".$uniq.$exe;	  
					$tiny_dir_name = $path.$tiny_img_name;
					$feat_img_name = "feat_".$uniq.$exe;
					$feat_dir_name = $path.$feat_img_name;
					
					$lrg_width = 200; $lrg_height = 230; $cmd_parms_l = "x230 -resize '460x<' -resize 50% -gravity center";
					$med_width = 100; $med_height = 120; $cmd_parms_m = "x120 -resize '240x<' -resize 30% -gravity center";
					$small_width = 83; $small_height = 100; $cmd_parms_s = "x100 -resize '200x<' -resize 30% -gravity center";
					$tiny_width = 44; $tiny_height = 54; $cmd_parms_t = "x44 -resize '200x<' -resize 30% -gravity center";
					$feat_width = 239; $feat_height = 120; $cmd_parms_f = "x239 -resize '200x<' -resize 30% -gravity center";
					$this->thumbnail($lrg_dir_name,$temp,$lrg_width,$lrg_height,$cmd_parms_l,$tag);
					$this->thumbnail($med_dir_name,$temp,$med_width,$med_height,$cmd_parms_m,$tag);
					$this->thumbnail($small_dir_name,$temp,$small_width,$small_height,$cmd_parms_s,$tag);	
					$this->thumbnail($tiny_dir_name,$temp,$tiny_width,$tiny_height,$cmd_parms_t,$tag);	
					$this->thumbnail($feat_dir_name,$temp,$feat_width,$feat_height,$cmd_parms_f,$tag);	
				}
		}
		copy($temp,$dir);
		return $img;
	}	 
}
?>