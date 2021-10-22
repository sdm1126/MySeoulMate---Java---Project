package kr.or.mrhi.MySeoulMate.Adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import java.util.ArrayList;

import kr.or.mrhi.MySeoulMate.Entity.Attraction;
import kr.or.mrhi.MySeoulMate.R;

public class LikeAdapter extends RecyclerView.Adapter<LikeAdapter.LikeViewHolder> {
    private Context context;
    private ArrayList<Attraction> likeList;

    // interface
    private OnLikeItemClickListener onLikeItemClickListener;

    public interface OnLikeItemClickListener {
        void onLikeItemClick(int position);
    }

    public void setOnLikeItemClickListener(LikeAdapter.OnLikeItemClickListener onLikeItemClickListener) {
        this.onLikeItemClickListener = onLikeItemClickListener;
    }

    public LikeAdapter(Context context, ArrayList<Attraction> likeList) {
        this.context = context;
        this.likeList = likeList;
    }

    @NonNull
    @Override
    public LikeViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.item_like, parent, false);
        LikeViewHolder likeViewHolder = new LikeViewHolder(view);
        return likeViewHolder;
    }

    @Override
    public void onBindViewHolder(@NonNull LikeViewHolder holder, int position) {
        if(holder instanceof LikeViewHolder) {
            holder.onLikeItemClickListener = onLikeItemClickListener;
        }

        holder.tv_title_item_like.setText(likeList.get(position).getTitle());
        holder.tv_addr1_item_like.setText(likeList.get(position).getAddr1());
    }

    @Override
    public int getItemCount() {
        return likeList.size();
    }

    @Override
    public int getItemViewType(int position) {
        return position;
    }

    public class LikeViewHolder extends RecyclerView.ViewHolder {
        private TextView tv_title_item_like;
        private TextView tv_addr1_item_like;

        // interface
        private OnLikeItemClickListener onLikeItemClickListener;

        public LikeViewHolder(@NonNull View itemView) {
            super(itemView);

            tv_title_item_like = itemView.findViewById(R.id.tv_title_item_like);
            tv_addr1_item_like = itemView.findViewById(R.id.tv_addr1_item_like);

            itemView.setTag(getAdapterPosition());
            itemView.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    onLikeItemClickListener.onLikeItemClick(getAdapterPosition());
                }
            });
        }
    }
}
