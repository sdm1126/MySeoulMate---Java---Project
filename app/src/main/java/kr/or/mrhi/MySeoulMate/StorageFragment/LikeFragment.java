package kr.or.mrhi.MySeoulMate.StorageFragment;

import android.annotation.SuppressLint;
import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AlertDialog;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.bumptech.glide.Glide;
import com.google.firebase.auth.FirebaseAuth;

import java.util.ArrayList;

import kr.or.mrhi.MySeoulMate.Activity.MapActivity;
import kr.or.mrhi.MySeoulMate.Adapter.LikeAdapter;
import kr.or.mrhi.MySeoulMate.Entity.Attraction;
import kr.or.mrhi.MySeoulMate.MySeoulMateDBHelper;
import kr.or.mrhi.MySeoulMate.R;

public class LikeFragment extends Fragment {

    // widget
    private RecyclerView rv_fragment_like;

    // data
    private LikeAdapter likeAdapter;
    private ArrayList<Attraction> likeList;
    private MySeoulMateDBHelper mySeoulMateDBHelper;

    // interface
    private LikeAdapter.OnLikeItemClickListener onLikeItemClickListener;

    // google
    private FirebaseAuth firebaseAuth;

    // etc
    boolean isLiked;

    public static LikeFragment getInstance() {
        LikeFragment likeFragment = new LikeFragment();
        return likeFragment;
    }

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_like, container, false);
        rv_fragment_like = view.findViewById(R.id.rv_fragment_like);

        // 데이터 & Adapter 생성
        mySeoulMateDBHelper = MySeoulMateDBHelper.getInstance(getContext());
        firebaseAuth = FirebaseAuth.getInstance();
        
        likeList = mySeoulMateDBHelper.loadLike(firebaseAuth.getCurrentUser().getUid());
        likeAdapter = new LikeAdapter(getContext(), likeList);

        // 좋아요 Item 클릭 시,
        onLikeItemClickListener = new LikeAdapter.OnLikeItemClickListener() {
            @SuppressLint("ResourceAsColor")
            @Override
            public void onLikeItemClick(int position) {
                // Dialog 생성
                View dialogView = View.inflate(getContext(), R.layout.dialog_like, null);
                AlertDialog.Builder builder = new AlertDialog.Builder(getContext());
                AlertDialog alertDialog = builder.create();
                alertDialog.setView(dialogView);
                alertDialog.show();

                // Dialog findViewById
                ImageView iv_like_dialog_like = dialogView.findViewById(R.id.iv_like_dialog_like);
                ImageView iv_map_dialog_like = dialogView.findViewById(R.id.iv_map_dialog_like);
                TextView tv_title_dialog_like = dialogView.findViewById(R.id.tv_title_dialog_like);
                TextView tv_addr1_dialog_like = dialogView.findViewById(R.id.tv_addr1_dialog_like);
                ImageView iv_image_dialog_like = dialogView.findViewById(R.id.iv_image_dialog_like);
                TextView tv_readcount_dialog_like = dialogView.findViewById(R.id.tv_readcount_dialog_like);
                Button btn_dialog_like = dialogView.findViewById(R.id.btn_dialog_like);
                
                // Dialog 내용 세팅
                // 1. 좋아요 아이콘
                for(Attraction attraction : likeList) {
                    if(likeList.get(position).getContentid().equals(attraction.getContentid())) {
                        isLiked = true;
                        iv_like_dialog_like.setImageResource(R.drawable.ic_like);
                    }
                }
                iv_like_dialog_like.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View view) {
                        if(isLiked == false) {
                            isLiked = true;
                            iv_like_dialog_like.setImageResource(R.drawable.ic_like);
                            mySeoulMateDBHelper.insertLike(firebaseAuth.getCurrentUser().getUid(), likeList.get(position));
                            showToast("좋아요가 설정되었습니다.");
                        } else {
                            isLiked = false;
                            iv_like_dialog_like.setImageResource(R.drawable.ic_unlike);
                            mySeoulMateDBHelper.deleteLike(firebaseAuth.getCurrentUser().getUid(), likeList.get(position));
                            showToast("좋아요가 해제되었습니다.");
                        }
                    }
                });
                // 2. 지도 열기 아이콘
                iv_map_dialog_like.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View view) {
                        Intent intent = new Intent(getActivity(), MapActivity.class);
                        intent.putExtra("arrayList", likeList);
                        intent.putExtra("position", position);
                        startActivity(intent);
                    }
                });
                // 3. 제목
                tv_title_dialog_like.setText(likeList.get(position).getTitle());
                // 4. 주소
                tv_addr1_dialog_like.setText(likeList.get(position).getAddr1());
                // 5. 사진
                if(likeList.get(position).getFirstimage() != null) {
                    Glide.with(getContext()).load(likeList.get(position).getFirstimage()).into(iv_image_dialog_like);
                } else {
                    Glide.with(getContext()).load(R.drawable.ic_no_image).into(iv_image_dialog_like);
                    tv_readcount_dialog_like.setTextColor(R.color.black);
                }
                // 6. 조회 수
                tv_readcount_dialog_like.setText("#조회 수 " + likeList.get(position).getReadcount() + "회");
                // 7. 닫기
                btn_dialog_like.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View view) {
                        alertDialog.dismiss();

                        likeList = mySeoulMateDBHelper.loadLike(firebaseAuth.getCurrentUser().getUid());
                        likeAdapter = new LikeAdapter(getContext(), likeList);
                        likeAdapter.setOnLikeItemClickListener(onLikeItemClickListener);
                        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(getContext());
                        rv_fragment_like.setLayoutManager(linearLayoutManager);
                        rv_fragment_like.setAdapter(likeAdapter);
                        likeAdapter.notifyDataSetChanged();
                    }
                });
            }
        };

        // Adapter 적용 & LinearLayoutManager 생성 및 적용
        likeAdapter.setOnLikeItemClickListener(onLikeItemClickListener);
        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(getContext());
        rv_fragment_like.setLayoutManager(linearLayoutManager);
        rv_fragment_like.setAdapter(likeAdapter);
        likeAdapter.notifyDataSetChanged();

        Log.d("확인", "LikeFragment_onCreateView()");
        return view;
    }

    private void showToast(String message) {
        Toast.makeText(getContext(), message, Toast.LENGTH_LONG).show();
    }
}
