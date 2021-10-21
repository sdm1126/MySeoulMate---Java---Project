package kr.or.mrhi.MySeoulMate.Activity;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.FragmentManager;
import androidx.fragment.app.FragmentTransaction;

import android.content.Intent;
import android.os.Bundle;
import android.os.PersistableBundle;
import android.view.MenuItem;

import com.google.android.material.bottomnavigation.BottomNavigationView;
import com.google.android.material.navigation.NavigationBarView;
import com.google.android.material.navigation.NavigationView;

import kr.or.mrhi.MySeoulMate.R;
import kr.or.mrhi.MySeoulMate.StorageFragment.AlbumFragment;
import kr.or.mrhi.MySeoulMate.StorageFragment.LikeFragment;

public class StorageActivity extends AppCompatActivity {
    // widget
    private BottomNavigationView bnv_storage;

    // fragment
    private FragmentManager fragmentManager;
    private FragmentTransaction fragmentTransaction;
    private AlbumFragment albumFragment;
    private LikeFragment likeFragment;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_storage);

        bnv_storage = findViewById(R.id.bnv_storage);
        bnv_storage.setOnItemSelectedListener(new NavigationBarView.OnItemSelectedListener() {
            @Override
            public boolean onNavigationItemSelected(@NonNull MenuItem item) {
                switch(item.getItemId()) {
                    case R.id.item_album:
                        setFragment(0);
                        return true;
                    case R.id.item_like:
                        setFragment(1);
                        return true;
                }
                return false;
            }
        });

        // Fragment별 객체 생성(각 Fragment 클래스 안에 Fragment View를 생성하는 구문 들어가있음)
        albumFragment = new AlbumFragment();
        likeFragment = new LikeFragment();

        // 화면 초기 세팅
        setFragment(0);
    }

    // Fragment 교체 실행문
    // bottom menu는 ArrayList식, n은 index에 해당
    private void setFragment(int n) {
        fragmentManager = getSupportFragmentManager();
        fragmentTransaction = fragmentManager.beginTransaction();
        switch (n) {
            case 0:
                fragmentTransaction.replace(R.id.fl_storage, albumFragment);
                break;
            case 1:
                fragmentTransaction.replace(R.id.fl_storage, likeFragment);
                break;
        }
        fragmentTransaction.commit();
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        Intent intent = new Intent(StorageActivity.this, AreaActivity.class);
        startActivity(intent);
        finish();
    }
}