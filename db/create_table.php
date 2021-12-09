<?php
    function create_table($con, $table_name) {
        $flag = false;
        $sql = "SHOW TABLES FROM theMiraeSeoul";
        $result = mysqli_query($con, $sql) or die("테이블 조회 실패".mysqli_error($con));
    
        // 테이블이 이미 존재하는 지 확인
        while($row = mysqli_fetch_row($result)) {
            if($row[0] === "$table_name") {
                $flag = true;
                break;
            }
        }

        // 테이블명에 따른 테이블 생성 쿼리문 설정
        if($flag === false) {
            switch($table_name) {
                // 1. 회원
                case 'user':
                    $sql = "CREATE TABLE IF NOT EXISTS user (
                        no INT(11) NOT NULL AUTO_INCREMENT,
                        last_name VARCHAR(20) NOT NULL DEFAULT '',
                        first_name VARCHAR(20) NOT NULL DEFAULT '',
                        full_name VARCHAR(40) NOT NULL DEFAULT '',
                        gender CHAR(3) NOT NULL DEFAULT '',
                        id VARCHAR(20) NOT NULL DEFAULT '',
                        password VARCHAR(255) NOT NULL DEFAULT '',
                        email1 VARCHAR(20) NOT NULL DEFAULT '',
                        email2 VARCHAR(20) NOT NULL DEFAULT '',
                        mobile1 CHAR(3) NOT NULL DEFAULT '',
                        mobile2 CHAR(4) NOT NULL DEFAULT '',
                        mobile3 CHAR(4) NOT NULL DEFAULT '',
                        registered_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                        PRIMARY KEY(no),
                        UNIQUE KEY(id),
                        KEY (registered_date)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                    break;

                // 2. 공지사항
                case 'notice':
                    $sql = "CREATE TABLE IF NOT EXISTS notice (
                        no INT(11) NOT NULL AUTO_INCREMENT,
                        title VARCHAR(255) NOT NULL DEFAULT '',
                        content VARCHAR(255) NOT NULL DEFAULT '',
                        written_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                        read_count INT NOT NULL DEFAULT 0,
                        PRIMARY KEY(no),
                        KEY (written_date)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                    break;

                // 3. FAQ
                case 'faq':
                    $sql = "CREATE TABLE IF NOT EXISTS faq (
                        no INT(11) NOT NULL AUTO_INCREMENT,
                        question VARCHAR(255) NOT NULL DEFAULT '',
                        answer VARCHAR(255) NOT NULL DEFAULT '',
                        PRIMARY KEY(no)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                    break;

                // 4. 문의하기
                case 'inquiry':
                    $sql = "CREATE TABLE IF NOT EXISTS inquiry (
                        no INT(11) NOT NULL AUTO_INCREMENT,
                        id VARCHAR(20) NOT NULL DEFAULT '',
                        title VARCHAR(255) NOT NULL DEFAULT '',
                        content VARCHAR(255) NOT NULL DEFAULT '',
                        attached_file VARCHAR(255) DEFAULT '',
                        written_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                        PRIMARY KEY (no),
                        KEY (written_date)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                    break;

                // 5. 댓글
                case 'comment':
                    $sql = "CREATE TABLE IF NOT EXISTS comment (
                        no INT(11) NOT NULL AUTO_INCREMENT,
                        id VARCHAR(20) NOT NULL DEFAULT '',
                        content VARCHAR(255) NOT NULL DEFAULT '',
                        inquiry_number INT(11) NOT NULL DEFAULT 0,
                        written_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                        PRIMARY KEY(no)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                    break;
      
                // 6. 객실예약
                case 'reservation':
                    $sql = "CREATE TABLE IF NOT EXISTS reservation (
                       no INT(11) NOT NULL AUTO_INCREMENT,
                       id VARCHAR(20) NOT NULL DEFAULT '',
                       full_name VARCHAR(40) NOT NULL DEFAULT '',
                       check_in DATE NOT NULL DEFAULT '0000-00-00',
                       check_out DATE NOT NULL DEFAULT '0000-00-00',
                       adult INT NOT NULL DEFAULT 0,
                       child INT NOT NULL DEFAULT 0,
                       deal_name VARCHAR(40) NOT NULL DEFAULT '',
                       room_type VARCHAR(3) NOT NULL DEFAULT '',
                       adult_breakfast INT NOT NULL DEFAULT 0,
                       child_breakfast INT NOT NULL DEFAULT 0,
                       total_tariff INT NOT NULL DEFAULT 0,
                       room_night INT NOT NULL DEFAULT 0,
                       cc_company VARCHAR(15) NOT NULL DEFAULT '',
                       cc_number VARCHAR(19) NOT NULL DEFAULT '',
                       cc_expiry_month CHAR(2) NOT NULL DEFAULT '',
                       cc_expiry_year CHAR(4) NOT NULL DEFAULT '',
                       special_request VARCHAR(255) NOT NULL DEFAULT '',
                       reservation_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                       PRIMARY KEY (no),
                       KEY(reservation_date)
                    )ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                    break;

                // 7. 객실상품
                case 'deal':
                    $sql = "CREATE TABLE IF NOT EXISTS deal (
                        no INT(11) NOT NULL AUTO_INCREMENT,
                        deal_image VARCHAR(255) NOT NULL DEFAULT '',
                        deal_name VARCHAR(40) NOT NULL DEFAULT '',
                        deal_content VARCHAR(255) NOT NULL DEFAULT '',
                        deal_start DATE NOT NULL DEFAULT '0000-00-00',
                        deal_end DATE NOT NULL DEFAULT '0000-00-00',
                        PRIMARY KEY(no)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                    break;

                // 8. 객실수량    
                case 'inventory':
                    $sql = "CREATE TABLE IF NOT EXISTS inventory (
                        inventory_date DATE NOT NULL DEFAULT '0000-00-00',
                        inventory_double INT NOT NULL DEFAULT 0,
                        inventory_twin INT NOT NULL DEFAULT 0,
                        inventory_triple INT NOT NULL DEFAULT 0,
                        inventory_grand INT NOT NULL DEFAULT 0,
                        PRIMARY KEY(inventory_date)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                    break;

                // 9. 객실요금
                case 'tariff':
                    $sql = "CREATE TABLE IF NOT EXISTS tariff (
                        tariff_date DATE NOT NULL DEFAULT '0000-00-00',
                        tariff_double INT NOT NULL DEFAULT 0,
                        tariff_twin INT NOT NULL DEFAULT 0,
                        tariff_triple INT NOT NULL DEFAULT 0,
                        tariff_grand INT NOT NULL DEFAULT 0,
                        discount_rate_room_only INT NOT NULL,
                        discount_rate_relaxing INT NOT NULL,
                        discount_rate_streaming INT NOT NULL,
                        discount_rate_everland INT NOT NULL,
                        discount_rate_winter INT NOT NULL,
                        discount_rate_christmas INT NOT NULL,
                        PRIMARY KEY(tariff_date)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                    break;

                // 10. 회원 기록
                case 'user_log':
                    $sql = "CREATE TABLE IF NOT EXISTS user_log (
                        no INT(11) NOT NULL AUTO_INCREMENT,
                        last_name VARCHAR(20) NOT NULL DEFAULT '',
                        first_name VARCHAR(20) NOT NULL DEFAULT '',
                        full_name VARCHAR(40) NOT NULL DEFAULT '',
                        gender CHAR(3) NOT NULL DEFAULT '',
                        id VARCHAR(20) NOT NULL DEFAULT '',
                        password VARCHAR(255) NOT NULL DEFAULT '',
                        email1 VARCHAR(20) NOT NULL DEFAULT '',
                        email2 VARCHAR(20) NOT NULL DEFAULT '',
                        mobile1 CHAR(3) NOT NULL DEFAULT '',
                        mobile2 CHAR(4) NOT NULL DEFAULT '',
                        mobile3 CHAR(4) NOT NULL DEFAULT '',
                        registered_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                        log_status CHAR(3) NOT NULL DEFAULT '',
                        log_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                        PRIMARY KEY(no),
                        KEY (log_date)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                    break;

                // 11. 예약 기록
                case 'reservation_log':
                    $sql = "CREATE TABLE IF NOT EXISTS reservation_log (
                        no INT(11) NOT NULL AUTO_INCREMENT,
                        id VARCHAR(20) NOT NULL DEFAULT '',
                        full_name VARCHAR(40) NOT NULL DEFAULT '',
                        check_in DATE NOT NULL DEFAULT '0000-00-00',
                        check_out DATE NOT NULL DEFAULT '0000-00-00',
                        adult INT NOT NULL DEFAULT 0,
                        child INT NOT NULL DEFAULT 0,
                        deal_name VARCHAR(40) NOT NULL DEFAULT '',
                        room_type VARCHAR(3) NOT NULL DEFAULT '',
                        adult_breakfast INT NOT NULL DEFAULT 0,
                        child_breakfast INT NOT NULL DEFAULT 0,
                        total_tariff INT NOT NULL DEFAULT 0,
                        room_night INT NOT NULL DEFAULT 0,
                        cc_company VARCHAR(15) NOT NULL DEFAULT '',
                        cc_number VARCHAR(19) NOT NULL DEFAULT '',
                        cc_expiry_month CHAR(2) NOT NULL DEFAULT '',
                        cc_expiry_year CHAR(4) NOT NULL DEFAULT '',
                        special_request VARCHAR(255) NOT NULL DEFAULT '',
                        reservation_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                        log_status CHAR(3) NOT NULL DEFAULT '',
                        log_date DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                        PRIMARY KEY(no),
                        KEY (log_date)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
                    break;

                // 12. 기타
                default:
                    echo "<script>alert('해당 테이블을 찾을 수 없습니다.');</script>";
                    break;
            }

            if(mysqli_query($con, $sql)) {
                echo "<script>alert('{$table_name} 테이블이 생성되었습니다.');</script>";
            } else {
                echo "<script>alert('{$table_name} 테이블이 생성되지 않았습니다.');</script>";
            }
        }
    }