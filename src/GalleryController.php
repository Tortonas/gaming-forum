<?php


class GalleryController extends MainController implements iController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function printPageView()
    {
        if($_SESSION['uzblokuotas'] === '1')
        {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Naudotojas neleistinai bandė prieiti prie puslapio", $ip);
            $this->redirect_to_another_page('index.php', 0);
        }
        if(isset($_POST['submitBtn']))
        {
            $error_flag = false;

            $file_tmp_name = $_FILES['photo']['tmp_name'];
            $file_size = $_FILES['photo']['size'];
            $file_error = $_FILES['photo']['error'];
            $file_type = $_FILES['photo']['type'];

            $file_ext = explode('.',$_FILES['photo']['name']);
            $file_actual_ext = strtolower(end($file_ext));

            //$allowed = 'png';

            $file_new_name = uniqid('',true).".".$file_actual_ext;
            $file_destination = "uploads/".$file_new_name;

            if ($file_actual_ext == "png" || $file_actual_ext == "jpg" || $file_actual_ext == "jpeg")
            {
                if($file_error === 0)
                {
                    if($file_size < 1000000)
                    {
                        move_uploaded_file($file_tmp_name,$file_destination);

                        $img_name = $_POST['img_name'];
                        $img_date = $this->getDateTime();
                        $img_user_id = $_SESSION['id'];

                        $img_id = $this->getModel()->gallery_insert_img($img_name,$file_destination,$file_actual_ext,$img_date,$img_user_id);

                        $tag_input = $_POST['tags'];

                        $seperated_tags = explode(";",$tag_input);

                        $tags = array();

                        foreach ( $seperated_tags as $tag )
                        {
                            if ( strlen($tag) > 0)
                            {
                                array_push($tags,$tag);
                            }
                        }

                        $inserted_tag_ids = array();
                        foreach ($tags as $tag)
                        {
                            $id = $this->getModel()->gallery_insert_tag($tag, $img_date);
                            if ($id == -1 )
                            {
                                $error_flag = true;
                                $ip = $this->getModel()->getIP();
                                $this->getModel()->updateLog("Nepavyko įkelti nuotraukos etikečių!", $ip);
                                $this->getView()->printDanger("Nepavyko įkelti nuotraukos etikečių!");
                                break;
                            }
                            array_push($inserted_tag_ids, $id);
                        }

                        if($error_flag != true)
                        {
                            foreach ($inserted_tag_ids as $tag_id)
                            {
                                $error_flag = $this->getModel()->gallery_assign_tag_to_img($img_id, $tag_id);
                                if($error_flag == true)
                                {
                                    $ip = $this->getModel()->getIP();
                                    $this->getModel()->updateLog("Nepavyko priskirti nuotraukų prie etikečių!", $ip);
                                    $this->getView()->printDanger("Nepavyko priskirti nuotraukų prie etikečių!");
                                    break;
                                }
                            }

                            $img_likes_id = $this->getModel()->gallery_assign_likes_to_img($img_id, $img_date);
                            if ($img_likes_id == -1)
                            {
                                $error_flag = true;
                                $ip = $this->getModel()->getIP();
                                $this->getModel()->updateLog("Nepavyko priskirti pamėgimų prie nuotraukos!", $ip);
                                $this->getView()->printDanger("Nepavyko priskirti pamėgimų prie nuotraukos!");
                            }

                            if($error_flag != true)
                            {
                                $ip = $this->getModel()->getIP();
                                $this->getModel()->updateLog("Nuotrauka sėkmingai įkelta", $ip);
                                $this->getView()->printSuccess("Nuotrauka sėkmingai įkelta");
                            }

                        }

                    }else {
                        $error_flag = true;
                        $ip = $this->getModel()->getIP();
                        $this->getModel()->updateLog("Nuotraukos dydis yra per didelis!", $ip);
                        $this->getView()->printDanger("Nuotraukos dydis yra per didelis!");
                    }
                }else
                {
                    $error_flag = true;
                    $ip = $this->getModel()->getIP();
                    $this->getModel()->updateLog("Nuotraukos nepavyko įkelti!", $ip);
                    $this->getView()->printDanger("Nuotraukos nepavyko įkelti!");
                }
            }else
            {
                $error_flag = true;
                $ip = $this->getModel()->getIP();
                $this->getModel()->updateLog("Nuotraukos formatas privalo būti PNG,JPG arba JPEG!", $ip);
                $this->getView()->printDanger("Nuotraukos formatas privalo būti PNG,JPG arba JPEG!");
            }
        }

        if (isset($_SESSION['message'])) {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog($_SESSION['message'], $ip);
            $this->getView()->printSuccess($_SESSION['message']);
            unset($_SESSION['message']);
        }

        if (isset($_SESSION['error'])) {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog($_SESSION['error'], $ip);
            $this->getView()->printDanger($_SESSION['error']);
            unset($_SESSION['error']);
        }

        if(isset($_POST['like_button']))
        {
            $img_id = $_POST['like_button'];

            $error_flag = $this->getModel()->gallery_increase_img_like_count($img_id);
            if($error_flag != true)
            {
                $ip = $this->getModel()->getIP();
                $this->getModel()->updateLog("Sėkmingai pavyko pamėgti nuotrauką!", $ip);
                $this->getView()->printSuccess("Sėkmingai pavyko pamėgti nuotrauką!");
            }else
            {
                $this->getView()->printDanger("Nepavyko pamėgti nuotraukos!");
            }
        }

        if(isset($_POST['delete_img']))
        {
            $error_flag = false;
            $img_id = $_POST['delete_img'];

            $img = $this->getModel()->gallery_get_image($img_id);

            $error_flag = $this->getModel()->gallery_delete_image($img_id);

            if ($error_flag != true)
            {
                $error_flag = !unlink($img['nuotraukos_kelias']);
                if ($error_flag != true)
                {
                    $ip = $this->getModel()->getIP();
                    $this->getModel()->updateLog("Sėkmingai pavyko ištrinti nuotrauką!", $ip);
                    $this->printSuccess("Sėkmingai pavyko ištrinti nuotrauką!");
                }else
                {
                    $ip = $this->getModel()->getIP();
                    $this->getModel()->updateLog("Nepavyko ištrinti nuotraukos iš katalogo", $ip);
                    $this->getView()->printDanger("Nepavyko ištrinti nuotraukos iš katalogo");
                }
            }else
            {
                $ip = $this->getModel()->getIP();
                $this->getModel()->updateLog("Nepavyko ištrinti nuotraukos iš duombazės", $ip);
                $this->getView()->printDanger("Nepavyko ištrinti nuotraukos iš duombazės");
            }

        }

        if($_SESSION['role'] > 0)
        {
            $this->getView()->print_gallery_image_upload();
        }

        $this->getView()->print_Gallery_frontpage();

        $images = $this->getModel()->gallery_get_all_imgs();
        if( $images != -1 )
        {
            foreach ($images as $image)
            {
                $this->getView()->print_gallery_images($image);
            }
        }else
        {
            //$this->getView()->printDanger("Nepavyko rasti nuotraukų duomabazėje!11111111");
        }
    }

    public function printCommentPageView()
    {
        $error_flag = false;
        if($_SESSION['uzblokuotas'] === '1')
        {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Naudotojas neleistinai bandė prieiti prie puslapio", $ip);
            $this->redirect_to_another_page('index.php', 0);
        }

        if(isset($_GET['img'])) {

            if (isset($_POST['like_button'])) {
                $img_id = $_POST['like_button'];

                $error_flag = $this->getModel()->gallery_increase_img_like_count($img_id);
                if($error_flag != true)
                {
                    $ip = $this->getModel()->getIP();
                    $this->getModel()->updateLog("Sėkmingai pavyko pamėgti nuotrauką!", $ip);
                    $this->getView()->printSuccess("Sėkmingai pavyko pamėgti nuotrauką!");
                }else
                {
                    $this->getView()->printDanger("Nepavyko pamėgti nuotraukos!");
                }
            }

            $img_id = $_GET['img'];

            if (isset($_SESSION['message'])) {
                $ip = $this->getModel()->getIP();
                $this->getModel()->updateLog($_SESSION['message'], $ip);
                $this->getView()->printSuccess($_SESSION['message']);
                unset($_SESSION['message']);
            }

            if (isset($_SESSION['error'])) {
                $ip = $this->getModel()->getIP();
                $this->getModel()->updateLog($_SESSION['error'], $ip);
                $this->getView()->printDanger($_SESSION['error']);
                unset($_SESSION['error']);
            }

            $img_data = $this->getModel()->gallery_get_image($img_id);
            if ($img_data != -1) {
                if (isset($_POST['delete_img'])) {

                    $error_flag = false;
                    $img_id = $_POST['delete_img'];

                    $img = $this->getModel()->gallery_get_image($img_id);

                    $error_flag = $this->getModel()->gallery_delete_image($img_id);

                    if ($error_flag != true)
                    {
                        $error_flag = !unlink($img['nuotraukos_kelias']);
                        if ($error_flag != true)
                        {

                            $_SESSION['message'] = "Sėkmingai pavyko ištrinti nuotrauką!";
                        }else
                        {
                            $_SESSION['error'] = "Nepavyko ištrinti nuotraukos iš katalogo";
                        }
                    }else
                    {
                        $_SESSION['error'] = "Nepavyko ištrinti nuotraukos iš duombazės";
                    }

                    $this->redirect_to_another_page("gallery.php",0);
                }

                if (isset($_POST['comment'])) {
                    $comment = $_POST['comment'];
                    $user_id = $_SESSION['id'];
                    $date = $img_date = $this->getDateTime();


                    $error_flag = $this->getModel()->gallery_add_image_comment($img_id, $user_id, $comment, $date);
                    if ($error_flag != true) {
                        $ip = $this->getModel()->getIP();
                        $this->getModel()->updateLog("Sėkmingai pavyko įkelti komentarą", $ip);
                        $this->getView()->printSuccess("Sėkmingai pavyko įkelti komentarą");
                    } else {
                        $ip = $this->getModel()->getIP();
                        $this->getModel()->updateLog("Nepavyko įkelti komentaro į duombazėję!", $ip);
                        $this->getView()->printDanger("Nepavyko įkelti komentaro į duombazėję!");
                    }
                }

                if (isset($_POST['delete_comment'])) {
                    $comment_id = $_POST['delete_comment'];

                    $error_flag = $this->getModel()->gallery_delete_image_comment($comment_id);
                    if ($error_flag != true) {
                        $ip = $this->getModel()->getIP();
                        $this->getModel()->updateLog("Sėkmingai pavyko ištrinti komentarą iš duombazės!", $ip);
                        $this->getView()->printSuccess("Sėkmingai pavyko ištrinti komentarą iš duombazės!");
                    } else {
                        $ip = $this->getModel()->getIP();
                        $this->getModel()->updateLog("Nepavyko ištrinti komentaro iš duombazės!", $ip);
                        $this->getView()->printDanger("Nepavyko ištrinti komentaro iš duombazės!");
                    }

                }


                $this->getView()->print_gallery_comment_section_image($img_data);
                if($_SESSION['role'] > 0) {
                    $this->getView()->print_gallery_comment_section_comment_form();
                }

                $comments = $this->getModel()->gallery_get_all_image_comments($img_id);
                if ($comments != -1) {
                    foreach ($comments as $comment) {
                        $this->getView()->print_gallery_comment_section_comment($comment);
                    }
                } else {
                    //$this->getView()->printDanger("Nepavyko gauti nuotraukos komentarų iš duombazės!");
                }

            } else {
                $ip = $this->getModel()->getIP();
                $this->getModel()->updateLog("Nepavyko rasti nuotraukos duombazėje!", $ip);
                $this->getView()->printDanger("Nepavyko rasti nuotraukos duombazėje!");
            }
        }else
        {
            $this->redirect_to_another_page('gallery.php',0);
        }
    }

    public function printCommentEditView()
    {
        $error_flag = false;
        if($_SESSION['uzblokuotas'] === '1' || empty($_SESSION['id']))
        {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Naudotojas neleistinai bandė prieiti prie puslapio", $ip);
            $this->redirect_to_another_page('gallery.php', 0);
        }

        if(isset($_GET['img']) && isset($_GET['comment_id'])) {
            $img_id = $_GET['img'];
            $comment_id = $_GET['comment_id'];

            if (isset($_POST['edit_comment']) && isset($_POST['text']))
            {
                $comment_id = $_POST['edit_comment'];
                $text = $_POST['text'];

                $error_flag = $this->getModel()->gallery_update_image_comment($comment_id,$text);
                if($error_flag != true)
                {
                    $_SESSION['message'] = "Pavyko sėkmingai atnaujinti komentarą!";
                    $this->redirect_to_another_page("viewphoto.php?img=".$img_id,0);
                }else
                {
                    $ip = $this->getModel()->getIP();
                    $this->getModel()->updateLog("Nepavyko atnaujinti komentaro duombazėje!", $ip);
                    $this->getView()->printDanger("Nepavyko atnaujinti komentaro duombazėje!");
                }
            }

            $comment = $this->getModel()->gallery_get_image_comment($comment_id);

            if($comment != -1)
            {
                $this->getView()->print_gallery_image_comment_edit($comment);

            }else
            {
                $ip = $this->getModel()->getIP();
                $this->getModel()->updateLog("Nepavyko rasti nuotraukos komentaro duombazėje!", $ip);
                $this->getView()->printDanger("Nepavyko rasti nuotraukos komentaro duombazėje!");
            }
        }else
        {
            $this->redirect_to_another_page('gallery.php',0);
        }
    }

    public function printImageSearchPageView()
    {
        if($_SESSION['uzblokuotas'] === '1')
        {
            $ip = $this->getModel()->getIP();
            $this->getModel()->updateLog("Naudotojas neleistinai bandė prieiti prie puslapio", $ip);
            $this->redirect_to_another_page('index.php',0);
        }

        $this->getView()->print_Gallery_searchpage();

        if (isset($_POST['search_img_submit']))
        {
            $jpg = false;
            $jpeg = false;
            $png = false;

            $img_name = false;

            $img_date = $_POST['img_upload_date'];

            $img_tags = false;

            if (isset($_POST['jpg']) && $_POST['jpg'] == 1)
            {
                $jpg = true;
            }
            if (isset($_POST['jpeg']) && $_POST['jpeg'] == 1)
            {
                $jpeg = true;
            }
            if (isset($_POST['png']) && $_POST['png'] == 1)
            {
                $png = true;
            }
            if (isset($_POST['img_name']) && strlen($_POST['img_name']) > 0)
            {
                $img_name = $_POST['img_name'];
            }

            if (isset($_POST['img_tags']) && $_POST['img_tags'] != "")
            {
                $img_tags = $_POST['img_tags'];

                $img_tags = explode(";",$img_tags);

                $tags = array();

                foreach ( $img_tags as $tag )
                {
                    if ( strlen($tag) > 0)
                    {
                        array_push($tags,$tag);
                    }
                }

                $img_tags = true;
            }

            $images = $this->getModel()->gallery_get_images_by_name_date_format($img_name,$jpg,$jpeg,$png,$img_date);
            if ($images != -1)
            {
                foreach ($images as $image)
                {
                    if ($img_tags == true)
                    {
                        $image_tag = $image['tags'];

                        $image_tag = explode(";",$image_tag);

                        $image_tag_array = [];

                        foreach ( $image_tag as $tag )
                        {
                            if ( strlen($tag) > 0)
                            {
                                array_push($image_tag_array,$tag);
                            }
                        }

                        if (count(array_intersect($image_tag_array, $tags)) > 0)
                        {
                            $this->getView()->print_gallery_images($image);
                        }
                    }else
                    {
                        $this->getView()->print_gallery_images($image);
                    }
                }
                $ip = $this->getModel()->getIP();
                $this->getModel()->updateLog("Nuotraukos paieška", $ip);
            }else
            {
                $ip = $this->getModel()->getIP();
                $this->getModel()->updateLog("Nuotraukų paieška duombazėje nepavyko!", $ip);
                $this->getView()->printWarning("Nuotraukų paieška duombazėje nepavyko!");
            }
        }

    }

    public function getTitle()
    {
        echo "Gaming Forum - Galerija";
    }
}