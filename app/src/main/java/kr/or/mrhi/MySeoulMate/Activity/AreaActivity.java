package kr.or.mrhi.MySeoulMate.Activity;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.constraintlayout.widget.ConstraintLayout;
import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentActivity;
import androidx.fragment.app.FragmentTransaction;
import androidx.viewpager2.adapter.FragmentStateAdapter;
import androidx.viewpager2.widget.ViewPager2;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.EditText;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.Toast;

import com.bumptech.glide.Glide;
import com.google.android.material.floatingactionbutton.FloatingActionButton;
import com.google.android.material.tabs.TabLayout;
import com.google.android.material.tabs.TabLayoutMediator;

import de.hdodenhof.circleimageview.CircleImageView;
import kr.or.mrhi.MySeoulMate.AreaFragment.CentralFragment;
import kr.or.mrhi.MySeoulMate.AreaFragment.NorthEastFragment;
import kr.or.mrhi.MySeoulMate.AreaFragment.NorthWestFragment;
import kr.or.mrhi.MySeoulMate.AreaFragment.SearchFragment;
import kr.or.mrhi.MySeoulMate.AreaFragment.SouthEastFragment;
import kr.or.mrhi.MySeoulMate.AreaFragment.SouthWestFragment;
import kr.or.mrhi.MySeoulMate.MySeoulMateDBHelper;
import kr.or.mrhi.MySeoulMate.R;
import kr.or.mrhi.MySeoulMate.Entity.User;
import me.relex.circleindicator.CircleIndicator3;

public class AreaActivity extends AppCompatActivity implements View.OnClickListener {

    final int TOTAL_NUMBER_OF_PAGES = 5;

    // widget
    private EditText et_area;
    private ImageView iv_area;
    private CircleImageView civ_area;
    private FrameLayout fl_area;
    private ConstraintLayout cl_area;
    private TabLayout tl_area;
    private ViewPager2 vp_area;
    private CircleIndicator3 ci_area;
    private FloatingActionButton fab_area;
    private FloatingActionButton fab1_area;
    private FloatingActionButton fab2_area;
    private FloatingActionButton fab3_area;

    // fragment
    private ViewPagerAdapter viewPagerAdapter;
    private TabLayoutMediator tabLayoutMediator;

    // animation
    private Intent intent;
    private Animation fab_open;
    private Animation fab_close;
    private boolean isFabOpen;

    // data
    private User user;

    // etc
    private boolean isSearching;
    private long backPressedTime;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_area);

        // findViewById
        et_area = findViewById(R.id.et_area);
        iv_area = findViewById(R.id.iv_area);
        civ_area = findViewById(R.id.civ_area);
        fl_area = findViewById(R.id.fl_area);
        cl_area = findViewById(R.id.cl_area);
        tl_area = findViewById(R.id.tl_area);
        vp_area = findViewById(R.id.vp_area);
        ci_area = findViewById(R.id.ci_area);
        fab_area = findViewById(R.id.fab_area);
        fab1_area = findViewById(R.id.fab1_area);
        fab2_area = findViewById(R.id.fab2_area);
        fab3_area = findViewById(R.id.fab3_area);

        // setOnClickListener
        iv_area.setOnClickListener(this);
        fab_area.setOnClickListener(this);
        fab1_area.setOnClickListener(this);
        fab2_area.setOnClickListener(this);
        fab3_area.setOnClickListener(this);

        // 플로팅 액션 버튼 애니메이션 효과
        fab_open = AnimationUtils.loadAnimation(getApplicationContext(), R.anim.fab_open);
        fab_close = AnimationUtils.loadAnimation(getApplicationContext(), R.anim.fab_close);
        isFabOpen = false;

        // 사용자 아이콘 세팅
        MySeoulMateDBHelper mySeoulMateDBHelper = MySeoulMateDBHelper.getInstance(getApplicationContext());
        user = mySeoulMateDBHelper.loadUser();
        Glide.with(getApplicationContext()).load(user.getUserImage()).into(civ_area);

        // ViewPagerAdapter 생성 및 적용
        viewPagerAdapter = new ViewPagerAdapter(this);
        vp_area.setAdapter(viewPagerAdapter);
        ci_area.setViewPager(vp_area);
        ci_area.createIndicators(TOTAL_NUMBER_OF_PAGES, 0);

        tabLayoutMediator = new TabLayoutMediator(tl_area, vp_area, new TabLayoutMediator.TabConfigurationStrategy() {
            @Override
            public void onConfigureTab(@NonNull TabLayout.Tab tab, int position) {
                switch(position) {
                    case 0: tab.setText("도심"); break;
                    case 1: tab.setText("동북"); break;
                    case 2: tab.setText("서북"); break;
                    case 3: tab.setText("동남"); break;
                    case 4: tab.setText("서남"); break;
                }
            }
        });
        tabLayoutMediator.attach();

        Log.d("확인", "AreaActivity_onCreate()");
    }

    @Override
    public void onClick(View view) {
        switch(view.getId()) {
            case R.id.iv_area:
                String keyword = et_area.getText().toString().trim(); // trim(): 공백 제거
                if(keyword.length() >= 2) {
                    fl_area.setVisibility(View.VISIBLE);
                    cl_area.setVisibility(View.INVISIBLE);

                    isSearching = true;

                    Bundle bundle = new Bundle();
                    bundle.putString("keyword", keyword);
                    SearchFragment searchFragment = SearchFragment.getInstance();
                    searchFragment.setArguments(bundle);
                    FragmentTransaction fragmentTransaction = getSupportFragmentManager().beginTransaction();
                    fragmentTransaction.replace(R.id.fl_area, searchFragment).commit();

                } else {
                    Toast.makeText(getApplicationContext(), "두 글자 이상 입력하세요.", Toast.LENGTH_SHORT).show();
                }
                break;
            case R.id.fab_area:
                anim();
                break;
            case R.id.fab1_area:
                anim();
                intent = new Intent(AreaActivity.this, LocationActivity.class);
                startActivity(intent);
                break;
            case R.id.fab2_area:
                anim();
                intent = new Intent(AreaActivity.this, StorageActivity.class);
                startActivity(intent);
                break;
            case R.id.fab3_area:
                anim();
                intent = new Intent(AreaActivity.this, SettingActivity.class);
                startActivity(intent);
                break;
        }
    }

    private void anim() {
        if(isFabOpen) {
            fab1_area.startAnimation(fab_close);
            fab2_area.startAnimation(fab_close);
            fab3_area.startAnimation(fab_close);
            fab1_area.setClickable(false);
            fab2_area.setClickable(false);
            fab3_area.setClickable(false);
            isFabOpen = false;
        } else {
            fab1_area.startAnimation(fab_open);
            fab2_area.startAnimation(fab_open);
            fab3_area.startAnimation(fab_open);
            fab1_area.setClickable(true);
            fab2_area.setClickable(true);
            fab3_area.setClickable(true);
            isFabOpen = true;
        }
    }

    private class ViewPagerAdapter extends FragmentStateAdapter {

        public ViewPagerAdapter(@NonNull FragmentActivity fragmentActivity) {
            super(fragmentActivity);
        }

        @NonNull
        @Override
        public Fragment createFragment(int position) {
            switch(position) {
                case 0: return CentralFragment.getInstance();
                case 1: return NorthEastFragment.getInstance();
                case 2: return NorthWestFragment.getInstance();
                case 3: return SouthEastFragment.getInstance();
                case 4: return SouthWestFragment.getInstance();
            }
            return null;
        }

        @Override
        public int getItemCount() {
            return TOTAL_NUMBER_OF_PAGES;
        }
    }

    @Override
    public void onBackPressed() {
        if(isSearching) {
            isSearching = false;
            et_area.setText("");
            fl_area.setVisibility(View.INVISIBLE);
            cl_area.setVisibility(View.VISIBLE);
            return;
        }

        long currentTime = System.currentTimeMillis();
        long gap = currentTime - backPressedTime;
        if(gap >= 0 && gap <= 2000) {
            super.onBackPressed();
            moveTaskToBack(true);
            finishAndRemoveTask();
            android.os.Process.killProcess(android.os.Process.myPid());
        } else {
            backPressedTime = currentTime;
            Toast.makeText(this, "한번 더 누르면 종료됩니다.", Toast.LENGTH_LONG).show();
        }
    }
}