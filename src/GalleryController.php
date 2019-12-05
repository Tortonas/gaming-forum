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
                        $img_date = date("Y-m-d");
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

        if(isset($_POST['like_button']))
        {
            echo '<h1>LIKED IMAGE!</h1>';
        }

        if(isset($_POST['delete_img']))
        {
            echo '<h1>DELETE IMAGE!</h1>';
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
            $this->getView()->printDanger("Nepavyko rasti nuotraukų duomabazėje!11111111");
        }
    }

    public function getTitle()
    {
        echo "Gaming Forum - Galerija";
    }
}