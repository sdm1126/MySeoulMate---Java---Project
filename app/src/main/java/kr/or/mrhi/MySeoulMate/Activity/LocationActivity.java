package kr.or.mrhi.MySeoulMate.Activity;

import static java.lang.Thread.sleep;

import android.Manifest;
import android.app.Activity;
import android.app.AlertDialog;
import android.app.FragmentManager;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.graphics.Bitmap;
import android.graphics.Canvas;
import android.location.Address;
import android.location.Geocoder;
import android.location.LocationManager;
import android.os.Bundle;
import android.util.DisplayMetrics;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.ImageView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;

import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.MapFragment;
import com.google.android.gms.maps.OnMapReadyCallback;
import com.google.android.gms.maps.model.BitmapDescriptorFactory;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.Marker;
import com.google.android.gms.maps.model.MarkerOptions;
import com.google.android.material.floatingactionbutton.FloatingActionButton;

import org.xmlpull.v1.XmlPullParser;
import org.xmlpull.v1.XmlPullParserFactory;

import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.URL;
import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

import kr.or.mrhi.MySeoulMate.Attraction;
import kr.or.mrhi.MySeoulMate.GpsTracker;
import kr.or.mrhi.MySeoulMate.R;

public class LocationActivity extends AppCompatActivity implements OnMapReadyCallback, View.OnClickListener {

    // widget
    private View view;
    private ImageView iv_marker_list;
    private FloatingActionButton fab_location;
    private FloatingActionButton fab1_location;
    private FloatingActionButton fab2_location;
    private FloatingActionButton fab3_location;

    // data
    private ArrayList<Attraction> arrayList = new ArrayList<>();
    private Attraction attraction;
    private String addr1;
    private String mapx;
    private String mapy;
    private String title;
    private String contentid;
    private Thread thread;

    // map
    private String[] REQUIRED_PERMISSIONS = {Manifest.permission.ACCESS_FINE_LOCATION, Manifest.permission.ACCESS_COARSE_LOCATION};
    private static final int GPS_ENABLE_REQUEST_CODE = 2001;
    private static final int PERMISSIONS_REQUEST_CODE = 100;
    private FragmentManager fragmentManager;
    private MapFragment mapFragment;
    private GpsTracker gpsTracker;
    private GoogleMap googleMap;
    private Marker marker;
    private MarkerOptions markerOptions;
    private LatLng location;
    private double latitude;
    private double longitude;

    // animation
    private Animation fab_open;
    private Animation fab_close;
    private boolean isFabOpen;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_location);

        gpsTracker = new GpsTracker(LocationActivity.this);
        latitude = gpsTracker.getLatitude();
        longitude = gpsTracker.getLongitude();
        location = new LatLng(latitude, longitude);

        // 위치 정보를 못 찾을 경우(GPS 기능 OFF), 즉시 Activity 종료
        if(latitude == 0.0 && longitude == 0.0) {
            Toast.makeText(getApplicationContext(), "위치 정보를 활성화해주세요.", Toast.LENGTH_SHORT).show();
            finish();
        }

        thread = new Thread() {
            @Override
            public void run() {
                // TODO Auto-generated method stub
                getXmlData(12);
//                getXmlData(14);
//                getXmlData(28);
//                getXmlData(32);
//                getXmlData(38);
//                getXmlData(39);
            }
        };
        thread.start();
        try {
            thread.join();
        } catch (InterruptedException e) {
            e.printStackTrace();
        }

        fab_location = findViewById(R.id.fab_location);
        fab1_location = findViewById(R.id.fab1_location);
        fab2_location = findViewById(R.id.fab2_location);
        fab3_location = findViewById(R.id.fab3_location);

        fab_location.setOnClickListener(this);
        fab1_location.setOnClickListener(this);
        fab2_location.setOnClickListener(this);
        fab3_location.setOnClickListener(this);

        fab_open = AnimationUtils.loadAnimation(getApplicationContext(), R.anim.fab_open);
        fab_close = AnimationUtils.loadAnimation(getApplicationContext(), R.anim.fab_close);
        isFabOpen = false;

        fragmentManager = getFragmentManager(); // getSupportFragmentManager() 아닌 점 주의
        mapFragment = (MapFragment) fragmentManager.findFragmentById(R.id.fm_location);
        mapFragment.getMapAsync(this);
    }

    private void getXmlData(int contenttypeid) {
        String tag;
        String queryUrl = "http://api.visitkorea.or.kr/openapi/service/rest/KorService/locationBasedList?serviceKey=" +
                MainActivity.KEY +
                "&MobileOS=AND&MobileApp=AppTest&arrange=E&contentTypeId=" + contenttypeid +
                "&mapX=" + longitude + "&mapY=" + latitude + "&radius=10000&listYN=Y";
        Log.d("위치", queryUrl);

        try {
            URL url = new URL(queryUrl);//문자열로 된 요청 url을 URL 객체로 생성.
            InputStream is = url.openStream(); //url위치로 입력스트림 연결

            XmlPullParserFactory factory = XmlPullParserFactory.newInstance();
            XmlPullParser xpp = factory.newPullParser();
            xpp.setInput(new InputStreamReader(is, "UTF-8")); //inputstream 으로부터 xml 입력받기

            xpp.next();
            int eventType = xpp.getEventType();

            while (eventType != XmlPullParser.END_DOCUMENT) {
                switch (eventType) {
                    case XmlPullParser.START_DOCUMENT:
                        break;

                    case XmlPullParser.START_TAG:
                        tag = xpp.getName();//태그 이름 얻어오기

                        if (tag.equals("item")) ;// 첫번째 검색결과
                        else if (tag.equals("addr1")) {
                            xpp.next();
                            addr1 = xpp.getText();
                        } else if (tag.equals("mapx")) {
                            xpp.next();
                            mapx = xpp.getText();
                        } else if (tag.equals("mapy")) {
                            xpp.next();
                            mapy = xpp.getText();
                        } else if (tag.equals("title")) {
                            xpp.next();
                            title = xpp.getText();
                        } else if (tag.equals("contentid")) {
                            xpp.next();
                            contentid = xpp.getText();
                            break;
                        }

                    case XmlPullParser.TEXT:
                        break;

                    case XmlPullParser.END_TAG:
                        tag = xpp.getName(); //태그 이름 얻어오기
                        if (tag.equals("item")) {
                            attraction = new Attraction(addr1, contentid, mapx, mapy, title);
                            arrayList.add(attraction);
                        }
                        break;
                }

                eventType = xpp.next();
            }

        } catch (Exception e) {
            Log.e("확인", e.toString());
        }
    }

    @Override
    public void onMapReady(@NonNull GoogleMap googleMap) {
        this.googleMap = googleMap;

        location = new LatLng(latitude, longitude);
        markerOptions = new MarkerOptions();
        markerOptions.title("현 위치");
        markerOptions.position(location);
        marker = googleMap.addMarker(markerOptions);

        setCustomMarkerView();
        drawMarker(googleMap);
        googleMap.animateCamera(CameraUpdateFactory.newLatLngZoom(location, 16));
    }

    @Override
    public void onClick(View view) {
        switch(view.getId()) {
            case R.id.fab_location:
                anim();
                break;
            case R.id.fab1_location:
                anim();
                gpsTracker = new GpsTracker(LocationActivity.this);
                marker.remove();
                location = new LatLng(latitude, longitude);
                markerOptions = new MarkerOptions();
                markerOptions.title("현 위치");
                markerOptions.position(location);
                marker = googleMap.addMarker(markerOptions);
                googleMap.animateCamera(CameraUpdateFactory.newLatLngZoom(location, 16));
                break;
            case R.id.fab2_location:
                anim();
                googleMap.setMapType(GoogleMap.MAP_TYPE_HYBRID);
                break;
            case R.id.fab3_location:
                anim();
                googleMap.setMapType(GoogleMap.MAP_TYPE_NORMAL);
                break;
        }
    }

    private void anim() {
        if(isFabOpen) {
            fab1_location.startAnimation(fab_close);
            fab2_location.startAnimation(fab_close);
            fab3_location.startAnimation(fab_close);
            fab1_location.setClickable(false);
            fab2_location.setClickable(false);
            fab3_location.setClickable(false);
            isFabOpen = false;
        } else {
            fab1_location.startAnimation(fab_open);
            fab2_location.startAnimation(fab_open);
            fab3_location.startAnimation(fab_open);
            fab1_location.setClickable(true);
            fab2_location.setClickable(true);
            fab3_location.setClickable(true);
            isFabOpen = true;
        }
    }

    private void setCustomMarkerView() {
        view = LayoutInflater.from(this).inflate(R.layout.marker_location, null);
        iv_marker_list = view.findViewById(R.id.iv_marker_list);
    }

    private void drawMarker(GoogleMap googleMap) {
        for (int i = 0; i < arrayList.size(); i++) {
            switch (arrayList.get(i).getContenttypeid()) {
                case "12": // 관광지
                    iv_marker_list.setImageResource(R.drawable.ic_check_circle);
                    break;
//                case "14": // 문화시설
//                    iv_marker_list.setImageResource(R.drawable.corporateculture);
//                    break;
//                case "28": // 레포츠
//                    iv_marker_list.setImageResource(R.drawable.running);
//                    break;
//                case "32": // 숙박
//                    iv_marker_list.setImageResource(R.drawable.hotel);
//                    break;
//                case "38": // 쇼핑
//                    iv_marker_list.setImageResource(R.drawable.shoppingcart);
//                    break;
//                case "39": // 음식점
//                    iv_marker_list.setImageResource(R.drawable.menu);
//                    break;
            }
            marker = googleMap.addMarker(new MarkerOptions()
                    .position(new LatLng(Double.parseDouble(arrayList.get(i).getMapy()), Double.parseDouble(arrayList.get(i).getMapx())))
                    .title(arrayList.get(i).getTitle())
                    .snippet(arrayList.get(i).getAddr1())
                    .icon(BitmapDescriptorFactory.fromBitmap(createDrawableFromView(this, view))));
        }
        return;
    }

    // View를 Bitmap으로 변환
    private Bitmap createDrawableFromView(Context context, View view) {
        DisplayMetrics displayMetrics = new DisplayMetrics();
        ((Activity) context).getWindowManager().getDefaultDisplay().getMetrics(displayMetrics);
        view.setLayoutParams(new ViewGroup.LayoutParams(ViewGroup.LayoutParams.WRAP_CONTENT, ViewGroup.LayoutParams.WRAP_CONTENT));
        view.measure(displayMetrics.widthPixels, displayMetrics.heightPixels);
        view.layout(0, 0, displayMetrics.widthPixels, displayMetrics.heightPixels);
        view.buildDrawingCache();
        Bitmap bitmap = Bitmap.createBitmap(view.getMeasuredWidth(), view.getMeasuredHeight(), Bitmap.Config.ARGB_8888);

        Canvas canvas = new Canvas(bitmap);
        view.draw(canvas);

        return bitmap;
    }

    // ActivityCompat.requestPermissions를 사용한 퍼미션 요청의 결과를 리턴받는 메소드입니다.
    @Override
    public void onRequestPermissionsResult(int permsRequestCode,
                                           @NonNull String[] permissions,
                                           @NonNull int[] grandResults) {

        super.onRequestPermissionsResult(permsRequestCode, permissions, grandResults);
        if (permsRequestCode == PERMISSIONS_REQUEST_CODE && grandResults.length == REQUIRED_PERMISSIONS.length) {

            // 요청 코드가 PERMISSIONS_REQUEST_CODE 이고, 요청한 퍼미션 개수만큼 수신되었다면
            boolean check_result = true;

            // 모든 퍼미션을 허용했는지 체크합니다.
            for (int result : grandResults) {
                if (result != PackageManager.PERMISSION_GRANTED) {
                    check_result = false;
                    break;
                }
            }

            // 위치 값을 가져올 수 있음
            if (check_result) {

            } else {
                // 거부한 퍼미션이 있다면 앱을 사용할 수 없는 이유를 설명해주고 앱을 종료합니다. 2가지 경우가 있습니다.
                if (ActivityCompat.shouldShowRequestPermissionRationale(this, REQUIRED_PERMISSIONS[0])
                        || ActivityCompat.shouldShowRequestPermissionRationale(this, REQUIRED_PERMISSIONS[1])) {
                    Toast.makeText(LocationActivity.this, "퍼미션이 거부되었습니다. 앱을 다시 실행하여 퍼미션을 허용해주세요.", Toast.LENGTH_LONG).show();
                    finish();

                } else {
                    Toast.makeText(LocationActivity.this, "퍼미션이 거부되었습니다. 설정(앱 정보)에서 퍼미션을 허용해야 합니다. ", Toast.LENGTH_LONG).show();
                }
            }
        }
    }

    private void checkRunTimePermission() {
        //런타임 퍼미션 처리
        // 1. 위치 퍼미션을 가지고 있는지 체크합니다.
        int hasFineLocationPermission = ContextCompat.checkSelfPermission(LocationActivity.this,
                Manifest.permission.ACCESS_FINE_LOCATION);
        int hasCoarseLocationPermission = ContextCompat.checkSelfPermission(LocationActivity.this,
                Manifest.permission.ACCESS_COARSE_LOCATION);


        if (hasFineLocationPermission == PackageManager.PERMISSION_GRANTED &&
                hasCoarseLocationPermission == PackageManager.PERMISSION_GRANTED) {

            // 2. 이미 퍼미션을 가지고 있다면
            // ( 안드로이드 6.0 이하 버전은 런타임 퍼미션이 필요없기 때문에 이미 허용된 걸로 인식합니다.)


            // 3.  위치 값을 가져올 수 있음


        } else {  //2. 퍼미션 요청을 허용한 적이 없다면 퍼미션 요청이 필요합니다. 2가지 경우(3-1, 4-1)가 있습니다.

            // 3-1. 사용자가 퍼미션 거부를 한 적이 있는 경우에는
            if (ActivityCompat.shouldShowRequestPermissionRationale(LocationActivity.this, REQUIRED_PERMISSIONS[0])) {

                // 3-2. 요청을 진행하기 전에 사용자가에게 퍼미션이 필요한 이유를 설명해줄 필요가 있습니다.
                Toast.makeText(LocationActivity.this, "이 앱을 실행하려면 위치 접근 권한이 필요합니다.", Toast.LENGTH_LONG).show();
                // 3-3. 사용자게에 퍼미션 요청을 합니다. 요청 결과는 onRequestPermissionResult에서 수신됩니다.
                ActivityCompat.requestPermissions(LocationActivity.this, REQUIRED_PERMISSIONS,
                        PERMISSIONS_REQUEST_CODE);


            } else {
                // 4-1. 사용자가 퍼미션 거부를 한 적이 없는 경우에는 퍼미션 요청을 바로 합니다.
                // 요청 결과는 onRequestPermissionResult에서 수신됩니다.
                ActivityCompat.requestPermissions(LocationActivity.this, REQUIRED_PERMISSIONS,
                        PERMISSIONS_REQUEST_CODE);
            }

        }

    }


    private String getCurrentAddress(double latitude, double longitude) {
        //지오코더... GPS를 주소로 변환
        Geocoder geocoder = new Geocoder(this, Locale.getDefault());
        List<Address> addresses;

        try {
            addresses = geocoder.getFromLocation(
                    latitude,
                    longitude,
                    7);

        } catch (IOException ioException) {
            //네트워크 문제
            Toast.makeText(this, "지오코더 서비스 사용불가", Toast.LENGTH_LONG).show();
            return "지오코더 서비스 사용불가";

        } catch (IllegalArgumentException illegalArgumentException) {
            Toast.makeText(this, "잘못된 GPS 좌표", Toast.LENGTH_LONG).show();
            return "잘못된 GPS 좌표";
        }

        if (addresses == null || addresses.size() == 0) {
            Toast.makeText(this, "주소 미발견", Toast.LENGTH_LONG).show();
            return "주소 미발견";
        }

        Address address = addresses.get(0);
        return address.getAddressLine(0).toString() + "\n";
    }


    //여기부터는 GPS 활성화를 위한 메소드들
    private void showDialogForLocationServiceSetting() {
        AlertDialog.Builder builder = new AlertDialog.Builder(LocationActivity.this);
        builder.setTitle("위치 서비스 비활성화");
        builder.setMessage("앱을 사용하기 위해서는 위치 서비스가 필요합니다.\n"
                + "위치 설정을 수정하시겠습니까?");
        builder.setCancelable(true);
        builder.setPositiveButton("설정", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int id) {
                Intent callGPSSettingIntent
                        = new Intent(android.provider.Settings.ACTION_LOCATION_SOURCE_SETTINGS);
                startActivityForResult(callGPSSettingIntent, GPS_ENABLE_REQUEST_CODE);
            }
        });
        builder.setNegativeButton("취소", new DialogInterface.OnClickListener() {
            @Override
            public void onClick(DialogInterface dialog, int id) {
                dialog.cancel();
            }
        });
        builder.create().show();
    }


    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);

        switch (requestCode) {
            case GPS_ENABLE_REQUEST_CODE:
                //사용자가 GPS 활성 시켰는지 검사
                if (checkLocationServicesStatus()) {
                    if (checkLocationServicesStatus()) {
                        Log.d("확인", "LocationActivity_onActivityResult(): GPS ON");
                        checkRunTimePermission();
                        return;
                    }
                }
                break;
        }
    }

    public boolean checkLocationServicesStatus() {
        LocationManager locationManager = (LocationManager) getSystemService(LOCATION_SERVICE);
        return locationManager.isProviderEnabled(LocationManager.GPS_PROVIDER)
                || locationManager.isProviderEnabled(LocationManager.NETWORK_PROVIDER);
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();

        Intent intent = new Intent(LocationActivity.this, AreaActivity.class);
        startActivity(intent);
        finish();
    }
}