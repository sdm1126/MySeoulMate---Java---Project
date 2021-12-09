<?php
    function create_trigger($con, $trigger_name) {
        $flag = false;
        $sql = "SHOW TRIGGERS WHERE `trigger` = '$trigger_name';"; // trigger 검색 시 ` 사용 요망(' 사용 시 검색 불가)
        $result = mysqli_query($con, $sql) or die("트리거 조회 실패".mysqli_error($con));

        if(mysqli_num_rows($result) > 0) {
            $flag = true;
        }

        if($flag === false) {
            switch($trigger_name) {
                case 'user_log_insert':
                    $sql = "CREATE TRIGGER user_log_insert
                        AFTER INSERT
                        ON user
                        FOR EACH ROW
                        BEGIN
                        INSERT into user_log VALUES (
                            null,
                            new.last_name,
                            new.first_name,
                            new.full_name,
                            new.gender,
                            new.id,
                            new.password,
                            new.email1,
                            new.email2,
                            new.mobile1,
                            new.mobile2,
                            new.mobile3,
                            new.registered_date,
                            '생성됨',
                            now()
                        );
                        END";
                    break;

                case 'user_log_update':
                    $sql = "CREATE TRIGGER user_log_update
                        AFTER UPDATE
                        ON user
                        FOR EACH ROW
                        BEGIN
                        INSERT into user_log VALUES (
                            null,
                            new.last_name,
                            new.first_name,
                            new.full_name,
                            new.gender,
                            new.id,
                            new.password,
                            new.email1,
                            new.email2,
                            new.mobile1,
                            new.mobile2,
                            new.mobile3,
                            new.registered_date,
                            '수정됨',
                            now()
                        );
                        END";
                    break;
                    
                case 'user_log_delete':
                    $sql = "CREATE TRIGGER user_log_delete
                        AFTER DELETE
                        ON user
                        FOR EACH ROW
                        BEGIN
                        INSERT into user_log VALUES (
                            null,
                            old.last_name,
                            old.first_name,
                            old.full_name,
                            old.gender,
                            old.id,
                            old.password,
                            old.email1,
                            old.email2,
                            old.mobile1,
                            old.mobile2,
                            old.mobile3,
                            old.registered_date,
                            '삭제됨',
                            now()
                        );
                        END";
                    break;

                case 'reservation_log_insert':
                    $sql = "CREATE TRIGGER reservation_log_insert
                        AFTER INSERT
                        ON reservation
                        FOR EACH ROW
                        BEGIN
                        INSERT into reservation_log VALUES (
                            null,
                            new.id,
                            new.full_name,
                            new.check_in,
                            new.check_out,
                            new.adult,
                            new.child,
                            new.deal_name,
                            new.room_type,
                            new.adult_breakfast,
                            new.child_breakfast,
                            new.total_tariff,
                            new.room_night,
                            new.cc_company,
                            new.cc_number,
                            new.cc_expiry_month,
                            new.cc_expiry_year,
                            new.special_request,
                            new.reservation_date,
                            '생성됨',
                            now()
                        );
                        END";
                    break;
                
                // case 'reservation_log_update':
                //     $sql = "CREATE TRIGGER reservation_log_update
                //         AFTER UPDATE
                //         ON reservation
                //         FOR EACH ROW
                //         BEGIN
                //         INSERT into reservation_log VALUES (
                //             null,
                //             new.id,
                //             new.full_name,
                //             new.check_in,
                //             new.check_out,
                //             new.adult,
                //             new.child,
                //             new.deal_name,
                //             new.room_type,
                //             new.adult_breakfast,
                //             new.child_breakfast,
                //             new.total_tariff,
                //             new.room_night,
                //             new.cc_company,
                //             new.cc_number,
                //             new.cc_expiry_month,
                //             new.cc_expiry_year,
                //             new.special_request,
                //             new.reservation_date,
                //             '수정됨',
                //             now()
                //         );
                //         END";
                //     break;

                case 'reservation_log_delete':
                    $sql = "CREATE TRIGGER reservation_log_delete
                        AFTER DELETE
                        ON reservation
                        FOR EACH ROW
                        BEGIN
                        INSERT into reservation_log VALUES (
                            null,
                            old.id,
                            old.full_name,
                            old.check_in,
                            old.check_out,
                            old.adult,
                            old.child,
                            old.deal_name,
                            old.room_type,
                            old.adult_breakfast,
                            old.child_breakfast,
                            old.total_tariff,
                            old.room_night,
                            old.cc_company,
                            old.cc_number,
                            old.cc_expiry_month,
                            old.cc_expiry_year,
                            old.special_request,
                            old.reservation_date,
                            '삭제됨',
                            now()
                        );
                        END";
                    break;
    
                default:
                    echo "<script>alert('해당 트리거가 없습니다.');</script>";
                    break;
            }
            
            if (mysqli_query($con, $sql)) {
                echo "<script>alert('{$trigger_name} 트리거가 생성되었습니다.');</script>";
            } else {
                echo "<script>alert('{$trigger_name} 트리거가 생성되지 않았습니다.');</script>";
            }
        }
    }
?>