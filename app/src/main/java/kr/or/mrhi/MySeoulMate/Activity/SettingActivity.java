package kr.or.mrhi.MySeoulMate.Activity;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.Toast;

import com.google.firebase.auth.FirebaseAuth;

import kr.or.mrhi.MySeoulMate.MySeoulMateDBHelper;
import kr.or.mrhi.MySeoulMate.R;

public class SettingActivity extends AppCompatActivity implements View.OnClickListener {

    // widget
    private Button btn_logout_setting;
    private Button btn_withdrawal_setting;
    private Button btn_information_setting;

    // google
    private FirebaseAuth firebaseAuth;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_setting);

        btn_logout_setting = findViewById(R.id.btn_logout_setting);
        btn_withdrawal_setting = findViewById(R.id.btn_withdrawal_setting);
        btn_information_setting = findViewById(R.id.btn_information_setting);

        btn_logout_setting.setOnClickListener(this);
        btn_withdrawal_setting.setOnClickListener(this);
        btn_information_setting.setOnClickListener(this);

        firebaseAuth = FirebaseAuth.getInstance();
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();

        Intent intent = new Intent(SettingActivity.this, AreaActivity.class);
        startActivity(intent);
        finish();
    }

    @Override
    public void onClick(View view) {
        switch(view.getId()) {
            case R.id.btn_logout_setting:
                // 구글로 로그인 했을 경우,
                if(firebaseAuth != null) {
                    firebaseAuth.signOut(); // 로그아웃
                    Intent intent = new Intent(SettingActivity.this, MainActivity.class);
                    startActivity(intent);
                    finish();
                    showToast("로그아웃 되었습니다.");
                }
                break;
            case R.id.btn_withdrawal_setting:
                // 구글로 로그인 했을 경우,
                if(firebaseAuth != null) {
                    MySeoulMateDBHelper mySeoulMateDBHelper = MySeoulMateDBHelper.getInstance(getApplicationContext());
                    mySeoulMateDBHelper.deleteUser(firebaseAuth.getCurrentUser().getUid());
                    mySeoulMateDBHelper.dropLike(firebaseAuth.getCurrentUser().getUid());
                    mySeoulMateDBHelper.dropAlbum(firebaseAuth.getCurrentUser().getUid());

                    firebaseAuth.getCurrentUser().delete(); // 연결 끊기
                    Intent intent = new Intent(SettingActivity.this, MainActivity.class);
                    startActivity(intent);
                    finish();
                    showToast("연결이 해제되었습니다.");
                }
                break;
            case R.id.btn_information_setting:
                break;
        }
    }

    private void showToast(String message) {
        Toast.makeText(getApplicationContext(), message, Toast.LENGTH_LONG).show();
    }
}