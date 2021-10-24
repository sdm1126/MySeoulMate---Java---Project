package kr.or.mrhi.MySeoulMate.StorageFragment;


import static android.app.Activity.RESULT_OK;
import static android.os.Environment.DIRECTORY_PICTURES;

import android.Manifest;
import android.app.AlertDialog;

import android.content.Intent;

import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Matrix;
import android.media.ExifInterface;
import android.net.Uri;
import android.os.Bundle;
import android.os.Environment;
import android.provider.MediaStore;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.Toast;

import com.google.firebase.auth.FirebaseAuth;
import com.gun0912.tedpermission.PermissionListener;
import com.gun0912.tedpermission.TedPermission;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.core.content.FileProvider;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.Locale;

import kr.or.mrhi.MySeoulMate.Adapter.AlbumAdapter;
import kr.or.mrhi.MySeoulMate.Entity.Album;
import kr.or.mrhi.MySeoulMate.MediaScanner;
import kr.or.mrhi.MySeoulMate.MySeoulMateDBHelper;
import kr.or.mrhi.MySeoulMate.R;

public class AlbumFragment extends Fragment {

    // widget
    private ImageView iv_fragment_album;
    private RecyclerView rv_fragment_album;
    private ImageView iv_image_dialog_album;

    // data
    private MySeoulMateDBHelper mySeoulMateDBHelper;
    private ArrayList<Album> albumList;
    private AlbumAdapter albumAdapter;
    private LinearLayoutManager linearLayoutManager;
    private static final int REQUEST_IMAGE_CAPTURE = 1001;
    private String imageFilePath;
    private Uri photoUri;
    private MediaScanner mediaScanner;

    // google
    private FirebaseAuth firebaseAuth;

    public static AlbumFragment getInstance() {
        AlbumFragment albumFragment = new AlbumFragment();
        return albumFragment;
    }

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_album, container, false);

        iv_fragment_album = view.findViewById(R.id.iv_fragment_album);
        rv_fragment_album = view.findViewById(R.id.rv_fragment_album);

        linearLayoutManager = new LinearLayoutManager(getContext());
        rv_fragment_album.setLayoutManager(linearLayoutManager);

        setInit();

        // 사진 저장 후 미디어 스캐닝을 돌려줘야 갤러리에 반영됨.
        mediaScanner = MediaScanner.getInstance(getContext());

        // 권한 체크
        TedPermission.with(getContext())
                .setPermissionListener(permissionListener)
                .setRationaleMessage("카메라 권한이 필요합니다.")
                .setDeniedMessage("카메라 권한이 거절되었습니다.")
                .setPermissions(Manifest.permission.WRITE_EXTERNAL_STORAGE, Manifest.permission.CAMERA)
                .check();

        Log.d("확인", "AlbumFragment_onCreateView()");
        return view;
    }

    public void setInit() {
        albumList = new ArrayList<>();
        mySeoulMateDBHelper = MySeoulMateDBHelper.getInstance(getContext());
        firebaseAuth = FirebaseAuth.getInstance();

        // 이전에 저장되어있던 데이터 불러오기
        loadRecentDB();

        // 팝업창 띄우기
        iv_fragment_album.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                View dialogView = View.inflate(getContext(), R.layout.dialog_album, null);
                AlertDialog.Builder builder = new AlertDialog.Builder(getContext());
                AlertDialog alertDialog = builder.create();
                alertDialog.setView(dialogView);

                EditText et_title_dialog_album = dialogView.findViewById(R.id.et_title_dialog_album);
                EditText et_content_dialog_album = dialogView.findViewById(R.id.et_content_dialog_album);
                iv_image_dialog_album = dialogView.findViewById(R.id.iv_image_dialog_album);
                Button btn_save_dialog_album = dialogView.findViewById(R.id.btn_save_dialog_album);
                Button btn_image_dialog_album = dialogView.findViewById(R.id.btn_image_dialog_album);

                btn_save_dialog_album.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View view) {
                        // 객체 생성 및 데이터베이스에 추가
                        Album album = new Album();
                        album.setAlbumTitle(et_title_dialog_album.getText().toString());
                        album.setAlbumContent(et_content_dialog_album.getText().toString());
                        String currentDate = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss").format(new Date());
                        album.setCurrentDate(currentDate);
                        album.setAlbumImage(imageFilePath);
                        mySeoulMateDBHelper.insertAlbum(firebaseAuth.getCurrentUser().getUid(), album);

                        imageFilePath = null; // 이미지 경로 초기화
                        albumAdapter.addItem(album); // Adapter에게 추가되었음을 알리는 역할
                        rv_fragment_album.smoothScrollToPosition(0);
                        alertDialog.dismiss();

                        Toast.makeText(getContext().getApplicationContext(), "목록에 추가되었습니다", Toast.LENGTH_SHORT).show();
                    }
                });

                btn_image_dialog_album.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        Intent intent = new Intent(MediaStore.ACTION_IMAGE_CAPTURE);
                        if (intent.resolveActivity(getContext().getPackageManager()) != null) {
                            File photoFile = null;
                            try {
                                photoFile = createImageFile();
                            } catch (IOException e) {

                            }

                            if (photoFile != null) {
                                photoUri = FileProvider.getUriForFile(getContext(), "kr.or.mrhi.MySeoulMate", photoFile);
                                intent.putExtra(MediaStore.EXTRA_OUTPUT, photoUri);
                                startActivityForResult(intent, REQUEST_IMAGE_CAPTURE);
                            }
                        }
                    }
                });

                alertDialog.show();
            }
        });
    }

    public File createImageFile() throws IOException {
        String timeStamp = new SimpleDateFormat("yyyyMMdd_HHmmss").format(new Date());
        String imageFileName = "TEST_" + timeStamp + "_";
        File storageDir = getContext().getExternalFilesDir(Environment.DIRECTORY_PICTURES);
        File image = File.createTempFile(
                imageFileName,
                ".jpg",
                storageDir
        );
        imageFilePath = image.getAbsolutePath();
        return image;
    }

    // 이전에 저장되어있던 DB가 있으면 불러온다.
    public void loadRecentDB() {
        albumList.clear();
        albumList = mySeoulMateDBHelper.loadAlbum(firebaseAuth.getCurrentUser().getUid());
        albumAdapter = new AlbumAdapter(requireActivity(), albumList);
        rv_fragment_album.setHasFixedSize(true); // RecyclerView 성능 강화
        rv_fragment_album.setAdapter(albumAdapter);
    }

    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (requestCode == REQUEST_IMAGE_CAPTURE && resultCode == RESULT_OK) {
            Bitmap bitmap = BitmapFactory.decodeFile(imageFilePath);
            ExifInterface exif = null;

            try {
                exif = new ExifInterface(imageFilePath);
            } catch (IOException e) {
                e.printStackTrace();
            }

            int exifOrientation;
            int exifDegree;

            if (exif != null) {
                exifOrientation = exif.getAttributeInt(ExifInterface.TAG_ORIENTATION, ExifInterface.ORIENTATION_NORMAL);
                exifDegree = exifOrientationToDegrees(exifOrientation);
            } else {
                exifDegree = 0;
            }

            String result = "";
            SimpleDateFormat simpleDateFormat = new SimpleDateFormat("yyyy-MM-dd HHmmss", Locale.getDefault());
            Date currentDate = new Date(System.currentTimeMillis());
            String filename = simpleDateFormat.format(currentDate);

            String strFolderName = Environment.getExternalStoragePublicDirectory(DIRECTORY_PICTURES) + File.separator + "My Seoul Mate" + File.separator;
            File file = new File(strFolderName);
            if (!file.exists())
                file.mkdirs();

            File f = new File(strFolderName + "/" + filename + ".png");
            result = f.getPath();

            FileOutputStream fOut = null;
            try {
                fOut = new FileOutputStream(f);
            } catch (FileNotFoundException e) {
                e.printStackTrace();
                result = "Save Error fOut";
            }

            // 비트맵 사진 폴더 경로에 저장
            rotate(bitmap, exifDegree).compress(Bitmap.CompressFormat.PNG, 70, fOut);

            try {
                fOut.flush();
            } catch (IOException e) {
                e.printStackTrace();
            }
            try {
                fOut.close();
                // 방금 저장된 사진을 갤러리 폴더 반영 및 최신화
                mediaScanner.mediaScanning(strFolderName + "/" + filename + ".png");
            } catch (IOException e) {
                e.printStackTrace();
                result = "File close Error";
            }

            // 이미지 뷰에 비트맵을 set하여 이미지 표현
            iv_image_dialog_album.setImageBitmap(rotate(bitmap, exifDegree));
        }
    }

    private int exifOrientationToDegrees(int exifOrientation) {
        if (exifOrientation == ExifInterface.ORIENTATION_ROTATE_90) {
            return 90;
        } else if (exifOrientation == ExifInterface.ORIENTATION_ROTATE_180) {
            return 180;
        } else if (exifOrientation == ExifInterface.ORIENTATION_ROTATE_270) {
            return 270;
        }
        return 0;
    }

    private Bitmap rotate(Bitmap bitmap, float degree) {
        Matrix matrix = new Matrix();
        matrix.postRotate(degree);
        return Bitmap.createBitmap(bitmap, 0, 0, bitmap.getWidth(), bitmap.getHeight(), matrix, true);
    }

    PermissionListener permissionListener = new PermissionListener() {
        @Override
        public void onPermissionGranted() {
            Toast.makeText(getContext(), "카메라 사용 권한이 허가되었습니다.", Toast.LENGTH_SHORT).show();
        }

        @Override
        public void onPermissionDenied(ArrayList<String> deniedPermissions) {
            Toast.makeText(getContext(), "카메라 사용 권한이 거부되었습니다.", Toast.LENGTH_SHORT).show();
        }
    };
}
