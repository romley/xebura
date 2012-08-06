<?PHP
function uniqimagename($file,$path)
{
 $temp=$_FILES[$file]['tmp_name'];
 $img=$_FILES[$file]['name'];
 if($img!="")
 {
  $pos = strrpos($img, ".");
  $len=strlen($img)-$pos;
  $exe=substr($img,$pos,$len);
  $img=md5($img.time().rand()).$exe;
  $dir=$path.$img;
  move_uploaded_file($temp,$dir);
  return $img;
 }
}
?>