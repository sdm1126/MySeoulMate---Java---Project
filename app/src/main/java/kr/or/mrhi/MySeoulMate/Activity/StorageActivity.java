package kr.or.mrhi.MySeoulMate.Activity;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.FragmentManager;
import androidx.fragment.app.FragmentTransaction;

import android.content.Intent;
import android.os.Bundle;
import android.view.MenuItem;
import android.widget.FrameLayout;

import com.google.android.material.bottomnavigation.BottomNavigationView;
import com.google.android.material.navigation.NavigationBarView;

import kr.or.mrhi.MySeoulMate.R;
import kr.or.mrhi.MySeoulMate.StorageFragment.AlbumFragment;
import kr.or.mrhi.MySeoulMate.StorageFragment.LikeFragment;

public class StorageActivity extends AppCompatActivity implements NavigationBarView.OnItemSelectedListener {

    // widget
    private FrameLayout fl_storage;
    private BottomNavigationView bnv_storage;

    // fragment
    private FragmentManager fragmentManager;
    private FragmentTransaction fragmentTransaction;
    private LikeFragment likeFragment;
    private AlbumFragment albumFragment;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_storage);

        fl_storage = findViewById(R.id.fl_storage);
        bnv_storage = findViewById(R.id.bnv_storage);

        bnv_storage.setOnItemSelectedListener(this);

        albumFragment = AlbumFragment.newInstance();
        likeFragment = LikeFragment.newInstance();

        fragmentManager = getSupportFragmentManager();
        fragmentTransaction = fragmentManager.beginTransaction();
        fragmentTransaction.replace(R.id.fl_storage, albumFragment).commit();
    }

    @Override
    public boolean onNavigationItemSelected(@NonNull MenuItem item) {
        fragmentManager = getSupportFragmentManager();
        fragmentTransaction = fragmentManager.beginTransaction();

        switch(item.getItemId()) {
            case R.id.item_album:
                fragmentTransaction.replace(R.id.fl_storage, albumFragment).commit();
                return true;
            case R.id.item_like:
                fragmentTransaction.replace(R.id.fl_storage, likeFragment).commit();
                return true;
        }
        return false;
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();

        Intent intent = new Intent(StorageActivity.this, AreaActivity.class);
        startActivity(intent);
        finish();
    }
}