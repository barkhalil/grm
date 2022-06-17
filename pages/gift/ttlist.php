<?php
$ListeCadeaux=get('*','grm_gift',array('famille='=>5));
$i=0;
foreach ($ListeCadeaux['reponse'] as $cade){

   // echo $cade['id'].'   '.$cade['code_article'].' '.getinfoByIdv3('bu','matgrm','idgrm='.$cade['id']).'<br>';

    $grp=getinfoByIdv3('bu','matgrm','idgrm='.$cade['id']);

  if($grp) {
      $data = array(


          'grp' => $grp


      );


      update($cade['id'], $data, 'grm_gift');
      $i++;
  }


}

echo $i;

?>