<?php
    // 가비아 호스팅 서비스 이용 시 경로 수정 예정
    include_once $_SERVER['DOCUMENT_ROOT']."/theMiraeSeoul/db/db_connector.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/theMiraeSeoul/db/create_table.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/theMiraeSeoul/db/create_procedure.php";
    include_once $_SERVER['DOCUMENT_ROOT']."/theMiraeSeoul/db/create_trigger.php";

    // 테이블
    create_table($con, "user");
    create_table($con, "notice");
    create_table($con, "faq");
    create_table($con, "inquiry");
    create_table($con, "comment");
    create_table($con, "reservation");
    create_table($con, "deal");
    create_table($con, "inventory");
    create_table($con, "tariff");
    create_table($con, "user_log");
    create_table($con, "reservation_log");

    // 프로시저
    create_procedure($con, "user_procedure");
    create_procedure($con, "faq_procedure");
    create_procedure($con, "reservation_insert_procedure");
    create_procedure($con, "reservation_delete_procedure");
    create_procedure($con, "deal_procedure");
    create_procedure($con, "tariff_procedure");
    create_procedure($con, "inventory_procedure");

    // 트리거
    create_trigger($con, "user_log_insert");
    create_trigger($con, "user_log_update");
    create_trigger($con, "user_log_delete");
    create_trigger($con, "reservation_log_insert");
    // create_trigger($con, "reservation_log_update");
    create_trigger($con, "reservation_log_delete");
?>