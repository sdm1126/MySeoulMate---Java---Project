package kr.or.mrhi.MySeoulMate.Adapter;

import android.annotation.SuppressLint;
import android.content.Context;
import android.content.Intent;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.bumptech.glide.Glide;
import com.google.firebase.auth.FirebaseAuth;

import java.util.ArrayList;

import kr.or.mrhi.MySeoulMate.Activity.MapActivity;
import kr.or.mrhi.MySeoulMate.Attraction;
import kr.or.mrhi.MySeoulMate.MySeoulMateDBHelper;
import kr.or.mrhi.MySeoulMate.R;

public class AreaAdapter extends RecyclerView.Adapter<AreaAdapter.AreaViewHolder> {

    private Context context;
    private ArrayList<Attraction> arrayList;
    private ArrayList<Attraction> likeList; // 좋아요 세팅용 리스트

    // interface
    private OnMapIconClickListener onMapIconClickListener;
    private OnImageIconClickListener onImageIconClickListener;

    // data
    private MySeoulMateDBHelper mySeoulMateDBHelper;

    // google
    private FirebaseAuth firebaseAuth;

    public interface OnMapIconClickListener {
        void onMapIconClick(int position);
    }

    public interface OnImageIconClickListener {
        void onImageIconClick(int position);
    }

    public void setOnMapIconClickListener(OnMapIconClickListener onMapIconClickListener) {
        this.onMapIconClickListener = onMapIconClickListener;
    }

    public void setOnImageIconClickListener(OnImageIconClickListener onImageIconClickListener) {
        this.onImageIconClickListener = onImageIconClickListener;
    }

    public AreaAdapter(Context context, ArrayList<Attraction> arrayList) {
        this.context = context;
        this.arrayList = arrayList;
    }

    @NonNull
    @Override
    public AreaAdapter.AreaViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.item_area, parent, false);
        AreaViewHolder areaViewHolder = new AreaViewHolder(view);

        Log.d("확인", "onCreateViewHolder()");
        return areaViewHolder;
    }

    @SuppressLint("ResourceAsColor")
    @Override
    public void onBindViewHolder(@NonNull AreaAdapter.AreaViewHolder holder, int position) {
        if(holder instanceof AreaViewHolder) {
            holder.onMapIconClickListener = onMapIconClickListener;
            holder.onImageIconClickListener = onImageIconClickListener;
        }

        // Item 내용 세팅
        // 1. 제목
        holder.tv_title_item_area.setText(arrayList.get(position).getTitle());
        // 2. 주소
        holder.tv_addr1_item_area.setText(arrayList.get(position).getAddr1());
        // 3. 사진
        if(arrayList.get(position).getFirstimage() != null) {
            Glide.with(context).load(arrayList.get(position).getFirstimage()).into(holder.iv_image_item_area);
        } else {
            holder.iv_image_item_area.setImageResource(R.drawable.ic_no_image);
            holder.tv_readcount_item_area.setTextColor(R.color.black);
        }
        // 4. 조회 수
        holder.tv_readcount_item_area.setText("#조회 수 " + arrayList.get(position).getReadcount() + "회");

        // 5. 좋아요
        mySeoulMateDBHelper = MySeoulMateDBHelper.getInstance(context.getApplicationContext());
        firebaseAuth = FirebaseAuth.getInstance();

        likeList = mySeoulMateDBHelper.loadLike(firebaseAuth.getCurrentUser().getUid());
        for(Attraction attraction : likeList) {
            if(arrayList.get(position).getContentid().equals(attraction.getContentid())) {
                holder.iv_like_item_area.setImageResource(R.drawable.ic_like);
                holder.isLiked = true;
            }
        }
    }

    @Override
    public int getItemCount() {
        return arrayList.size();
    }

    /* 재사용하는 과정에서 발생하는 문제를 해결하기 위해 각 아이템 별 뷰 타입을 요청
       (기존 생성된 ViewHolder를 제거, 재사용되지 않도록 함) */
    @Override
    public int getItemViewType(int position) {
        return position;
    }

    public class AreaViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener {
        // widget
        private ImageView iv_like_item_area;
        private ImageView iv_map_item_area;
        private TextView tv_title_item_area;
        private TextView tv_addr1_item_area;
        private ImageView iv_image_item_area;
        private TextView tv_readcount_item_area;

        // interface
        private OnMapIconClickListener onMapIconClickListener;
        private OnImageIconClickListener onImageIconClickListener;

        // data
        private MySeoulMateDBHelper mySeoulMateDBHelper;

        // google
        private FirebaseAuth firebaseAuth;

        // etc.
        private boolean isLiked;

        public AreaViewHolder(@NonNull View itemView) {
            super(itemView);

            // Item findViewById()
            iv_like_item_area = itemView.findViewById(R.id.iv_like_item_area);
            iv_map_item_area = itemView.findViewById(R.id.iv_map_item_area);
            tv_title_item_area = itemView.findViewById(R.id.tv_title_item_area);
            tv_addr1_item_area = itemView.findViewById(R.id.tv_addr1_item_area);
            iv_image_item_area = itemView.findViewById(R.id.iv_image_item_area);
            tv_readcount_item_area = itemView.findViewById(R.id.tv_readcount_item_area);

            // Item setOnClickListener()
            mySeoulMateDBHelper = MySeoulMateDBHelper.getInstance(itemView.getContext());
            firebaseAuth = FirebaseAuth.getInstance();

            iv_like_item_area.setOnClickListener(this);
            iv_map_item_area.setOnClickListener(this);
            iv_image_item_area.setOnClickListener(this);
        }

        @Override
        public void onClick(View view) {
            switch(view.getId()) {
                // 좋아요 아이콘 클릭 시,
                case R.id.iv_like_item_area:
                    if(isLiked == false) {
                        isLiked = true;
                        iv_like_item_area.setImageResource(R.drawable.ic_like);
                        mySeoulMateDBHelper.insertLike(firebaseAuth.getCurrentUser().getUid(), arrayList.get(getAdapterPosition()));
                        showToast("좋아요 설정");
                    } else {
                        isLiked = false;
                        iv_like_item_area.setImageResource(R.drawable.ic_unlike);
                        mySeoulMateDBHelper.deleteLike(firebaseAuth.getCurrentUser().getUid(), arrayList.get(getAdapterPosition()));
                        showToast("좋아요 해제");
                    }
                    break;
                // 지도 열기 아이콘 클릭 시,
                case R.id.iv_map_item_area:
                    onMapIconClickListener.onMapIconClick(getAdapterPosition());
                    break;
                // 이미지 아이콘 클릭 시,
                case R.id.iv_image_item_area:
                    onImageIconClickListener.onImageIconClick(getAdapterPosition());
                    break;
            }
        }

        private void showToast(String message) {
            Toast.makeText(context.getApplicationContext(), message, Toast.LENGTH_LONG).show();
        }
    }
}
