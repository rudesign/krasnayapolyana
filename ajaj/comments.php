<?php
class Ajaj{

    private $html = '';
    private $thread = array();
    private $i = 0;
    private $count = 0;
    private $depth = 0;

    public function __construct(){
        try{

            @include 'init.php';

            $this->check();

            $this->execute();

            echo json_encode(array('html'=>$this->html));
        } catch(Error $e){
            echo json_encode(array('message'=>$e->getMessage()));
        }
    }

    private function execute(){
        $this->getThread();

        $this->renderThread();
    }

    private function getThread(){
        if($this->thread = Comments::getThread(null, $_POST['page'])){
            if($this->count = count($this->thread[0])){
                $this->thread[0] = array_slice($this->thread[0], ($_POST['page']*Comments::$limit), Comments::$limit);
            }
        }
    }

    private function renderThread(){
        if($this->thread){
            $this->getBranchByParent();
        }else{
            $this->html .= 'Ваш будет первым:<br /><br />';
        }
    }

    private function getBranchByParent($parent = 0){
        if(!empty($this->thread[$parent])){

            $this->html .= '<div class="branch" id="b'.$parent.'">';

            foreach($this->thread[$parent] as $row){

                $thumbnail = getThumbnail($row['userGallery']);

                $this->html .= '
                <div class="items mini-section" id="i'.$row['id'].'">
                    '.( (($row['createdBy'] == Users::$current['id']) || Users::isEditor(Users::$current)) ? '<div class="del" onclick="delComment('.$row['id'].');">×</div>' : '').'
                    <div class="round-face">
                        <ul class="l">
                            <li class="avatar"'.($thumbnail ? ' style="background-image: url(/images/avatars/small/'.$thumbnail[0].')"' : '').'><i></i></li>
                            '.($row['senderName'] ? '<li class="b">'.$row['senderName'].'</li>' : '<li class="name '.(Users::isOnline($row) ? 'online' : 'offline').'"><a href="/people/'.$row['createdBy'].'.html">'.$row['userName'].'</a></li>').'
                            <li>'.$row['userCity'].'</li>
                        </ul>
                        <ul class="added">
                            <li>'.humanuzeTime(getRealTime($row['createdTime']), true).'</li>
                        </ul>

                        <div class="clear"></div>
                    </div>
                    <div class="small body">
                        <div class="micro-section">'.cleanOutgoingUri($row['name']).'</div>
                        '.(Users::$current ? '<dl class="reply small"><a onClick="placeCommentsForm(this, '.$row['id'].');" href="javascript:void(0);">Ответить</a></dl>' : '').'
                    </div>
                    <div class="clear"></div>
                </div>';

                $this->getBranchByParent($row['id']);

                if(!$parent) {

                    $this->i++;

                    $shown = $this->i + (Comments::$limit*$_POST['page']);

                    if(($this->count > Comments::$limit) && (($this->i == Comments::$limit) || ($shown == $this->count))){

                        $this->html .= '<div class="comments-pager bordered">';

                        if($_POST['page']){
                            $this->html .= '<dt><a onClick="showComments('.($_POST['page']-1).');" href="javascript:void(0);">Назад</a></dt>';
                        }

                        if($shown < $this->count) {
                            $this->html .= '<dt><a onClick="showComments('.($_POST['page']+1).');" href="javascript:void(0);">Вперёд</a></dt>';
                        }

                        $this->html .= '</div>';

                        break;

                    }

                }
            }


            $this->html .= '</div>';
        }
    }

    private function check(){
        if(empty($_POST)) throw new Error('No data passed');

        Users::route($_SERVER['HTTP_REFERER']);

        if(!Users::$accessGranted) die();

        if(!Chapters::$current['table']['name'] || !Chapters::$current['table']['primary'])  throw new Error('Insufficient SQL table info');

        if(empty(Router::$id)) throw new Error('No item id');
    }
}

$ajaj = new Ajaj();
?>