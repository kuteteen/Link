<?php
    include 'Link-Secure-Session.php';
    include 'Link-Check-Login.php';
    include_once 'Link-DB-Connect.php';

    secure_session_start();
    if(check_login($mysqli) == true){
        if(isset($_POST['text']) && isset($_POST['user_id'])&& isset($_POST['u_id']) && isset($_POST['chat_id'])){
            
            if($_POST['chat_id'] != 0){
                //add new message to database
                $insert = $mysqli->prepare("INSERT INTO message (text, user_id, user_name, chat_id) VALUES (?,?,?,?)");

                //$update = $mysqli->prepare("UPDATE chat SET last_updated=NOW() WHERE id=?");

                if($insert){
                   // $update->bind_param("i", $_POST['chat_id']);
                    $insert->bind_param("sisi", $_POST['text'], $_POST['user_id'], $_SESSION['user_name'], $_POST['chat_id']);
                    //$update->execute(); 
                    $insert->execute();

                    $id = $mysqli->insert_id;
                    $request = $mysqli->prepare("SELECT id, text, created_at, user_id, user_name FROM message WHERE id = $id");
                    $request->execute();
                    $request->store_result();
                    $request->bind_result($id, $text, $created_at, $user_id, $user_name);
                    if($request->num_rows > 0){
                        include 'Link-Message-Template.php';
                    }
                }else{
                    //echo"Message was not sent";
                }
            }
            
        }else{
            //echo "Message was not sent";
        }
    }else{
        //echo "User not logged in";
    }
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

