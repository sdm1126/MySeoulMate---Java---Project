<?php
            // 1. GET으로 넘어온 값이 있는지 체크 page, option, search_str 
            if(isset($_GET['page'])){
                $current_page = $_GET['page'];
            }else{
                $current_page = 1;
            }
            
            if(isset($_GET['option'])){
                $option = $_GET['option'];
            }else{
                $option = 'title';
            }

            if(isset($_GET['search_str'])){
                $search_str = $_GET['search_str'];
            }else{
                $search_str = '';
            }
            
            // 총 게시글 갯수를 구한다
            $sql = "SELECT COUNT(*) AS `total_count` FROM inquiry WHERE id = '$id' AND $option LIKE '%$search_str%' ";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $total_count = $row['total_count'];
            
            // 게시판 한 페이지에 들어갈 게시글 갯수 
            $page_row = 10;

            //총 페이지 갯수 구하기
            $total_page = ($total_count !== 0)? ceil($total_count / $page_row) : (0);

            //URL에 넣은 page값을 받아온다. 0번부터 시작
            $first_index = ($current_page - 1) * $page_row;
           
            // 실제 게시글 데이터 꺼내오기
            $sql = "SELECT * FROM inquiry WHERE id = '$id' AND  $option LIKE '%$search_str%' ORDER BY written_date desc LIMIT {$first_index}, {$page_row} ";    
            $result = mysqli_query($con, $sql);
            $list = array();

            // 결과값을 배열에 넣기 (2차원 배열)
            for ($i = 0; $row = mysqli_fetch_assoc($result); $i++) {
                $list[$i] = $row;
                $list[$i]['num'] = $total_count - $first_index - $i;
            }
            
            // url 구하기 (페이지 설정용)
            $http_host = $_SERVER['HTTP_HOST'];
            $request_uri = $_SERVER['REQUEST_URI'];
            $url = 'http://' . $http_host . $request_uri;

            // 처음 , 끝 페이지 구하기
            $start_page = (int)(($current_page -1) / $page_row) * $page_row  + 1;
            $end_page = (($start_page + $page_row - 1) < $total_page) ? ($start_page + $page_row - 1) : ($total_page);
            
             // 페이지 매기기
            $index_page = get_paging($page_row, $current_page, $total_page, $url);
           
            