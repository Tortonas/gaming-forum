<?php


class GalleryController extends MainController implements iController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function printPageView()
    {
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
                                    $this->getView()->printDanger("Nepavyko priskirti nuotraukų prie etikečių!");
                                    break;
                                }
                            }

                            $img_likes_id = $this->getModel()->gallery_assign_likes_to_img($img_id, $img_date);
                            if ($img_likes_id == -1)
                            {
                                $error_flag = true;
                                $this->getView()->printDanger("Nepavyko priskirti pamėgimų prie nuotraukos!");
                            }

                            if($error_flag != true)
                            {
                                $this->getView()->printSuccess("Nuotrauka sėkmingai įkelta");
                            }

                        }

                    }else {
                        $error_flag = true;
                        $this->getView()->printDanger("Nuotraukos dydis yra per didelis!");
                    }
                }else
                {
                    $error_flag = true;
                    $this->getView()->printDanger("Nuotraukos nepavyko įkelti!");
                }
            }else
            {
                $error_flag = true;
                $this->getView()->printDanger("Nuotraukos formatas privalo būti PNG,JPG arba JPEG!");
            }
        }

        if (isset($_SESSION['message'])) {
            $this->getView()->printSuccess($_SESSION['message']);
            unset($_SESSION['message']);
        }

        if (isset($_SESSION['error'])) {
            $this->getView()->printDanger($_SESSION['error']);
            unset($_SESSION['error']);
        }

        if(isset($_POST['like_button']))
        {
            echo '<h1>LIKED IMAGE!</h1>';
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
                    $this->printSuccess("Sėkmingai pavyko ištrinti nuotrauką!");
                }else
                {
                    $this->getView()->printDanger("Nepavyko ištrinti nuotraukos iš katalogo");
                }
            }else
            {
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

        if(isset($_GET['img'])) {

            $img_id = $_GET['img'];

            if (isset($_SESSION['message'])) {
                $this->getView()->printSuccess($_SESSION['message']);
                unset($_SESSION['message']);
            }

            if (isset($_SESSION['error'])) {
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

                if (isset($_POST['like_button'])) {
                    echo '<h1>LIKE!</h1>';
                }

                if (isset($_POST['comment'])) {
                    $comment = $_POST['comment'];
                    $user_id = $_SESSION['id'];
                    $date = $img_date = $this->getDateTime();


                    $error_flag = $this->getModel()->gallery_add_image_comment($img_id, $user_id, $comment, $date);
                    if ($error_flag != true) {
                        $this->getView()->printSuccess("Sėkmingai pavyko įkelti komentarą");
                    } else {
                        $this->getView()->printDanger("Nepavyko įkelti komentaro į duombazėję!");
                    }
                }

                if (isset($_POST['delete_comment'])) {
                    $comment_id = $_POST['delete_comment'];

                    $error_flag = $this->getModel()->gallery_delete_image_comment($comment_id);
                    if ($error_flag != true) {
                        $this->getView()->printSuccess("Sėkmingai pavyko ištrinti komentarą iš duombazės!");
                    } else {
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
                    $this->getView()->printDanger("Nepavyko atnaujinti komentaro duombazėje!");
                }
            }

            $comment = $this->getModel()->gallery_get_image_comment($comment_id);

            if($comment != -1)
            {
                $this->getView()->print_gallery_image_comment_edit($comment);

            }else
            {
                $this->getView()->printDanger("Nepavyko rasti nuotraukos komentaro duombazėje!");
            }
        }else
        {
            $this->redirect_to_another_page('gallery.php',0);
        }
    }

    public function getTitle()
    {
        echo "Gaming Forum - Galerija";
    }
}