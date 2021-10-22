package kr.or.mrhi.MySeoulMate.Adapter;

import android.app.Dialog;
import android.content.Context;
import android.content.DialogInterface;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AlertDialog;
import androidx.cardview.widget.CardView;
import androidx.recyclerview.widget.RecyclerView;


import com.bumptech.glide.Glide;
import com.google.firebase.auth.FirebaseAuth;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;


import kr.or.mrhi.MySeoulMate.MySeoulMateDBHelper;
import kr.or.mrhi.MySeoulMate.R;
import kr.or.mrhi.MySeoulMate.Entity.Album;

public class AlbumAdapter extends RecyclerView.Adapter<AlbumAdapter.ViewHolder> {

    private Context context;
    private ArrayList<Album> albumList;

    private MySeoulMateDBHelper mySeoulMateDBHelper;
    private FirebaseAuth firebaseAuth;
    private int currentPosition;

    public AlbumAdapter(Context context, ArrayList<Album> albumList) {
        this.context = context;
        this.albumList = albumList;

        mySeoulMateDBHelper = MySeoulMateDBHelper.getInstance(context);
        firebaseAuth = FirebaseAuth.getInstance();
    }

    @NonNull
    @Override
    public AlbumAdapter.ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View holder = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_album, parent, false);
        return new ViewHolder(holder);
    }

    /* 매개변수로 전달 받은 position을 넣을 경우,
       delete 과정에서 Inconsistency Detected 발생(새로고침이 다 되기 전 아이템 항목을 불러와서 문제 발생) 하여 currentPosition(holder.getAdapterPosition())으로 변경 */
    @Override
    public void onBindViewHolder(@NonNull AlbumAdapter.ViewHolder holder, int position) {
        holder.tv_title_item_album.setText(albumList.get(holder.getAdapterPosition()).getTitle());
        holder.tv_content_item_album.setText(albumList.get(holder.getAdapterPosition()).getContent());
        holder.tv_currentDate_item_album.setText(albumList.get(holder.getAdapterPosition()).getCurrentDate());
    }

    @Override
    public int getItemCount() {
        return albumList.size();
    }

    @Override
    public int getItemViewType(int position) {
        return position;
    }

    public class ViewHolder extends RecyclerView.ViewHolder {

        private TextView tv_title_item_album;
        private TextView tv_content_item_album;
        private TextView tv_currentDate_item_album;

        public ViewHolder(@NonNull View itemView) {
            super(itemView);

            tv_title_item_album = itemView.findViewById(R.id.tv_title_item_album);
            tv_content_item_album = itemView.findViewById(R.id.tv_content_item_album);
            tv_currentDate_item_album = itemView.findViewById(R.id.tv_currentDate_item_album);

            itemView.setTag(getAdapterPosition());
            itemView.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View view) {
                    currentPosition = getAdapterPosition(); // 현재 Item의 위치
                    Album album = albumList.get(currentPosition);

                    String[] strChoiceItems = {"수정하기", "삭제하기"};
                    AlertDialog.Builder builder = new AlertDialog.Builder(context);
                    builder.setTitle("원하는 작업을 선택하세요");
                    builder.setItems(strChoiceItems, new DialogInterface.OnClickListener() {
                        @Override
                        public void onClick(DialogInterface dialogInterface, int position) {
                            // 수정하기
                            if (position == 0) {
                                Dialog dialog = new Dialog(context, android.R.style.Theme_Material_Light_Dialog);
                                dialog.setContentView(R.layout.dialog_album);

                                EditText et_title_dialog_album = dialog.findViewById(R.id.et_title_dialog_album);
                                EditText et_content_dialog_album = dialog.findViewById(R.id.et_content_dialog_album);
                                ImageView iv_image_dialog_album = dialog.findViewById(R.id.iv_image_dialog_album);
                                CardView cardView5 = dialog.findViewById(R.id.cardView5);
                                Button btn_save_dialog_album = dialog.findViewById(R.id.btn_save_dialog_album);
                                btn_save_dialog_album.setText("수정");

                                et_title_dialog_album.setText(album.getTitle());
                                et_title_dialog_album.setSelection(et_title_dialog_album.getText().length()); // 수정 켰을 때 커서가 제목 가장 마지막에 가 있도록 함
                                et_content_dialog_album.setText(album.getContent());
                                if(album.getImage() == null) {
                                    iv_image_dialog_album.setVisibility(View.INVISIBLE);
                                } else {
                                    iv_image_dialog_album.setScaleType(ImageView.ScaleType.FIT_XY);
                                    Glide.with(context).load(album.getImage()).into(iv_image_dialog_album);
                                }
                                Log.d("확인", "album.getImage(): " + album.getImage());
                                cardView5.setVisibility(View.INVISIBLE);

                                btn_save_dialog_album.setOnClickListener(new View.OnClickListener() {
                                    @Override
                                    public void onClick(View view) {
                                        // 데이터베이스에 반영
                                        String title = et_title_dialog_album.getText().toString(); // 이전에 작성한 제목을 가져온다.
                                        String content = et_content_dialog_album.getText().toString();
                                        String currentDate = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss").format(new Date());
                                        String previousDate = album.getCurrentDate();
                                        mySeoulMateDBHelper.updateAlbum(firebaseAuth.getCurrentUser().getUid(), title, content, currentDate, previousDate); // beforeTime이 WHERE절로 들어간다.

                                        // RecyclerView에 반영
                                        album.setTitle(title);
                                        album.setContent(content);
                                        album.setCurrentDate(currentDate);
                                        notifyItemChanged(currentPosition, album);
                                        dialog.dismiss();

                                        Toast.makeText(context, "내용이 수정되었습니다", Toast.LENGTH_SHORT).show();
                                    }
                                });
                                dialog.show();

                            // 삭제하기
                            } else if (position == 1) {
                                // 데이터베이스에 반영
                                String previousDate = album.getCurrentDate();
                                mySeoulMateDBHelper.deleteAlbum(firebaseAuth.getCurrentUser().getUid(), previousDate);

                                // RecyclerView에 반영
                                albumList.remove(currentPosition);
                                notifyDataSetChanged();
                                Toast.makeText(context, "목록에서 삭제되었습니다", Toast.LENGTH_SHORT).show();
                            }
                        }
                    });
                    builder.show();
                }
            });
        }
    }

    // Fragment에서 호출되는 함수, 현재 AdapterView에 새롭게 게시할 Item을 전달받아 추가하는 목적
    public void addItem(Album item) {
        // 최신 데이터가 맨 위로 올라오게 함
        albumList.add(0, item);
        notifyItemInserted(0);
    }
}


