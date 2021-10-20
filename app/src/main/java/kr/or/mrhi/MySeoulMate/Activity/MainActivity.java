package kr.or.mrhi.MySeoulMate.Activity;

import android.Manifest;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.ImageButton;
import android.widget.ImageView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;

import com.google.android.gms.auth.api.Auth;
import com.google.android.gms.auth.api.signin.GoogleSignInAccount;
import com.google.android.gms.auth.api.signin.GoogleSignInOptions;
import com.google.android.gms.auth.api.signin.GoogleSignInResult;
import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.SignInButton;
import com.google.android.gms.common.api.GoogleApiClient;
import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.Task;
import com.google.firebase.auth.AuthCredential;
import com.google.firebase.auth.AuthResult;
import com.google.firebase.auth.FirebaseAuth;
import com.google.firebase.auth.GoogleAuthProvider;

import kr.or.mrhi.MySeoulMate.MySeoulMateDBHelper;
import kr.or.mrhi.MySeoulMate.R;
import kr.or.mrhi.MySeoulMate.User;

public class MainActivity extends AppCompatActivity
        implements View.OnClickListener, GoogleApiClient.OnConnectionFailedListener {

    public static final String KEY = "WB%2FKFtQpqhM4crWOn0JtKrTtQAxKlqupu%2BQmIG3QXH%2Biqez62WWkET7wO99U6zLOzlymB6z7Uhls3gpu%2F3sCZg%3D%3D";
    public static final String APP_NAME = "MySeoulMate";

    // widget
    private ImageButton ib_kakao_main; // 카카오 로그인 버튼
    private SignInButton sib_google_main; // 구글 로그인 버튼

    // data
    private User user;

    // google
    private GoogleSignInOptions googleSignInOptions;
    private GoogleApiClient googleApiClient; // 구글 API 클라이언트 객체
    private FirebaseAuth firebaseAuth; // 파이어베이스 인증 객체
    private final int REQUEST_CODE_GOOGLE = 1001; // 구글 로그인 결과 코드

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        checkPermissionGranted();

        ib_kakao_main = findViewById(R.id.ib_kakao_main);
        sib_google_main = findViewById(R.id.sib_google_main);

        ib_kakao_main.setOnClickListener(this);
        sib_google_main.setOnClickListener(this);

        googleSignInOptions = new GoogleSignInOptions.Builder(GoogleSignInOptions.DEFAULT_SIGN_IN)
                .requestIdToken(getString(R.string.default_web_client_id))
                .requestEmail()
                .build();

        googleApiClient = new GoogleApiClient.Builder(this)
                .enableAutoManage(this, this)
                .addApi(Auth.GOOGLE_SIGN_IN_API, googleSignInOptions)
                .build();

        firebaseAuth = FirebaseAuth.getInstance();
    }

    private void checkPermissionGranted() {
        int checkPermission1 = ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION);
        int checkPermission2 = ActivityCompat.checkSelfPermission(this, Manifest.permission.ACCESS_COARSE_LOCATION);
        if (checkPermission1 != PackageManager.PERMISSION_GRANTED &&
            checkPermission2 != PackageManager.PERMISSION_GRANTED) {
            ActivityCompat.requestPermissions(this,
                    new String[]{ Manifest.permission.ACCESS_FINE_LOCATION,
                                  Manifest.permission.ACCESS_COARSE_LOCATION },
                    MODE_PRIVATE);
        }
        Log.d("확인", "권한 승인 여부 체크(-1: 미승인, 0: 승인): " + checkPermission1 + " " + checkPermission2);
    }

    @Override
    public void onClick(View view) {
        switch(view.getId()) {
            case R.id.ib_kakao_main:
                break;
            case R.id.sib_google_main:
                Intent intent = Auth.GoogleSignInApi.getSignInIntent(googleApiClient);
                startActivityForResult(intent, REQUEST_CODE_GOOGLE);
                break;
        }
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, @Nullable Intent data) {
        super.onActivityResult(requestCode, resultCode, data);

        if(requestCode == REQUEST_CODE_GOOGLE) {
            // 인증 결과
            GoogleSignInResult googleSignInResult = Auth.GoogleSignInApi.getSignInResultFromIntent(data);
            // 인증 결과가 성공이면, 계정 데이터를 받음
            if(googleSignInResult.isSuccess()) {
                GoogleSignInAccount googleSignInAccount = googleSignInResult.getSignInAccount(); // googleSignInAccount에는 모든 계정 정보(프로필 사진 포함)가 담겨 있음
                resultLogIn(googleSignInAccount); // 로그인 결과값 출력
            }
        }
    }
    
    private void resultLogIn(GoogleSignInAccount googleSignInAccount) {
        AuthCredential authCredential = GoogleAuthProvider.getCredential(googleSignInAccount.getIdToken(), null);
        firebaseAuth.signInWithCredential(authCredential)
                .addOnCompleteListener(this, new OnCompleteListener<AuthResult>() {
                    @Override
                    public void onComplete(@NonNull Task<AuthResult> task) {
                        if(task.isSuccessful()) {
                            user = new User(
                                    firebaseAuth.getCurrentUser().getUid(),
                                    firebaseAuth.getCurrentUser().getDisplayName(),
                                    String.valueOf(firebaseAuth.getCurrentUser().getPhotoUrl()));

                            MySeoulMateDBHelper mySeoulMateDBHelper = MySeoulMateDBHelper.getInstance(getApplicationContext());
                            mySeoulMateDBHelper.insertUser(user);
                            mySeoulMateDBHelper.createLike(user);
                            mySeoulMateDBHelper.createAlbum(user);

                            Intent intent = new Intent(MainActivity.this, AreaActivity.class);
                            showToast("안녕하세요, " + firebaseAuth.getCurrentUser().getDisplayName() + "님! 반갑습니다.");
                            startActivity(intent);
                            finish();
                        } else {
                            showToast("다시 시도해주세요.");
                        }
                    }
                });
    }

    @Override
    public void onConnectionFailed(@NonNull ConnectionResult connectionResult) {

    }

    private void showToast(String message) {
        Toast.makeText(getApplicationContext(), message, Toast.LENGTH_LONG).show();
    }
}
