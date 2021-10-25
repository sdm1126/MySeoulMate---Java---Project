package kr.or.mrhi.MySeoulMate.Activity;

import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
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
                View dialogView = View.inflate(getApplicationContext(), R.layout.dialog_setting, null);
                AlertDialog.Builder builder = new AlertDialog.Builder(this);
                AlertDialog alertDialog = builder.create();

                // 전화
                ImageView iv_call1_dialog_setting = dialogView.findViewById(R.id.iv_call1_dialog_setting);
                ImageView iv_call2_dialog_setting = dialogView.findViewById(R.id.iv_call2_dialog_setting);
                ImageView iv_call3_dialog_setting = dialogView.findViewById(R.id.iv_call3_dialog_setting);
                ImageView iv_call4_dialog_setting = dialogView.findViewById(R.id.iv_call4_dialog_setting);
                iv_call1_dialog_setting.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View view) {
                        Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse("tel://010-2942-8263"));
                        startActivity(intent);
                    }
                });
                iv_call2_dialog_setting.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View view) {
                        Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse("tel://010-5671-1126"));
                        startActivity(intent);
                    }
                });
                iv_call3_dialog_setting.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View view) {
                        Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse("tel://010-9437-7519"));
                        startActivity(intent);
                    }
                });
                iv_call4_dialog_setting.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View view) {
                        Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse("tel://010-5668-4136"));
                        startActivity(intent);
                    }
                });

                // 문자
                ImageView iv_sms1_dialog_setting = dialogView.findViewById(R.id.iv_sms1_dialog_setting);
                ImageView iv_sms2_dialog_setting = dialogView.findViewById(R.id.iv_sms2_dialog_setting);
                ImageView iv_sms3_dialog_setting = dialogView.findViewById(R.id.iv_sms3_dialog_setting);
                ImageView iv_sms4_dialog_setting = dialogView.findViewById(R.id.iv_sms4_dialog_setting);
                iv_sms1_dialog_setting.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View view) {
                        Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse("sms:010-2942-8263"));
                        startActivity(intent);
                    }
                });
                iv_sms2_dialog_setting.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View view) {
                        Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse("sms://010-5671-1126"));
                        startActivity(intent);
                    }
                });
                iv_sms3_dialog_setting.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View view) {
                        Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse("sms://010-9437-7519"));
                        startActivity(intent);
                    }
                });
                iv_sms4_dialog_setting.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View view) {
                        Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse("sms://010-5668-4136"));
                        startActivity(intent);
                    }
                });

                Button btn_dialog_setting = dialogView.findViewById(R.id.btn_dialog_setting);
                btn_dialog_setting.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View view) {
                        alertDialog.dismiss();
                    }
                });

                alertDialog.setView(dialogView);
                alertDialog.show();
                break;
        }
    }

    private void showToast(String message) {
        Toast.makeText(getApplicationContext(), message, Toast.LENGTH_LONG).show();
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();

        Intent intent = new Intent(SettingActivity.this, AreaActivity.class);
        startActivity(intent);
        finish();
    }
}