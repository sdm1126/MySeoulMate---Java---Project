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
    private ArrayList<Attraction> likeList;

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

        holder.tv_title_item_area.setText(arrayList.get(position).getTitle());
        holder.tv_addr1_item_area.setText(arrayList.get(position).getAddr1());
        if(arrayList.get(position).getFirstimage() != null) {
            Glide.with(context).load(arrayList.get(position).getFirstimage()).into(holder.iv_image_item_area);
        } else {
            holder.iv_image_item_area.setImageResource(R.drawable.ic_no_image);
            holder.tv_readcount_item_area.setTextColor(R.color.black);
        }
        holder.tv_readcount_item_area.setText("#조회 수 " + arrayList.get(position).getReadcount() + "회");


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

    @Override
    public int getItemViewType(int position) {
        return position;
    }

    public class AreaViewHolder extends RecyclerView.ViewHolder implements View.OnClickListener {
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

        // etc
        private boolean isLiked;

        public AreaViewHolder(@NonNull View itemView) {
            super(itemView);

            iv_like_item_area = itemView.findViewById(R.id.iv_like_item_area);
            iv_map_item_area = itemView.findViewById(R.id.iv_map_item_area);
            tv_title_item_area = itemView.findViewById(R.id.tv_title_item_area);
            tv_addr1_item_area = itemView.findViewById(R.id.tv_addr1_item_area);
            iv_image_item_area = itemView.findViewById(R.id.iv_image_item_area);
            tv_readcount_item_area = itemView.findViewById(R.id.tv_readcount_item_area);

            iv_like_item_area.setOnClickListener(this);
            iv_map_item_area.setOnClickListener(this);
            iv_image_item_area.setOnClickListener(this);

            mySeoulMateDBHelper = MySeoulMateDBHelper.getInstance(itemView.getContext());
            firebaseAuth = FirebaseAuth.getInstance();
        }

        @Override
        public void onClick(View view) {
            switch(view.getId()) {
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
                case R.id.iv_map_item_area:
                    onMapIconClickListener.onMapIconClick(getAdapterPosition());
                    break;
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
