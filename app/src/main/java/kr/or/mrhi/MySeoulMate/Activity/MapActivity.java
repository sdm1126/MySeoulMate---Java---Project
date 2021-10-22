package kr.or.mrhi.MySeoulMate.Activity;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;

import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.MapFragment;
import com.google.android.gms.maps.OnMapReadyCallback;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.MarkerOptions;
import com.google.android.material.floatingactionbutton.FloatingActionButton;

import java.util.ArrayList;

import kr.or.mrhi.MySeoulMate.Entity.Attraction;
import kr.or.mrhi.MySeoulMate.R;

public class MapActivity extends AppCompatActivity implements OnMapReadyCallback, View.OnClickListener {

    // widget
    private MapFragment fm_map;
    private FloatingActionButton fab_map;
    private FloatingActionButton fab1_map;
    private FloatingActionButton fab2_map;
    private FloatingActionButton fab3_map;

    // animation
    private Animation fab_open;
    private Animation fab_close;
    private boolean isFabOpen;

    // map
    private GoogleMap googleMap;

    // data
    private ArrayList<Attraction> arrayList;
    private int position;
    private String mapx; // 경도(longitude)
    private String mapy; // 위도(latitude)

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_map);

        fab_map = findViewById(R.id.fab_map);
        fab1_map = findViewById(R.id.fab1_map);
        fab2_map = findViewById(R.id.fab2_map);
        fab3_map = findViewById(R.id.fab3_map);

        fab_map.setOnClickListener(this);
        fab1_map.setOnClickListener(this);
        fab2_map.setOnClickListener(this);
        fab3_map.setOnClickListener(this);

        fab_open = AnimationUtils.loadAnimation(getApplicationContext(), R.anim.fab_open);
        fab_close = AnimationUtils.loadAnimation(getApplicationContext(), R.anim.fab_close);
        isFabOpen = false;

        Intent intent = getIntent();
        arrayList = (ArrayList<Attraction>) intent.getSerializableExtra("arrayList");
        position = intent.getIntExtra("position", Integer.MAX_VALUE);
        mapx = arrayList.get(position).getMapx();
        mapy = arrayList.get(position).getMapy();

        fm_map = (MapFragment) getFragmentManager().findFragmentById(R.id.fm_map);
        fm_map.getMapAsync(this);
    }

    @Override
    public void onMapReady(@NonNull GoogleMap googleMap) {
        this.googleMap = googleMap;
        findAttractionOnMap();
    }

    @Override
    public void onClick(View view) {
        switch(view.getId()) {
            case R.id.fab_map:
                anim();
                break;
            case R.id.fab1_map:
                anim();
                findAttractionOnMap();
                break;
            case R.id.fab2_map:
                anim();
                googleMap.setMapType(GoogleMap.MAP_TYPE_HYBRID);
                break;
            case R.id.fab3_map:
                anim();
                googleMap.setMapType(GoogleMap.MAP_TYPE_NORMAL);
                break;
        }
    }

    private void anim() {
        if(isFabOpen) {
            fab1_map.startAnimation(fab_close);
            fab2_map.startAnimation(fab_close);
            fab3_map.startAnimation(fab_close);
            fab1_map.setClickable(false);
            fab2_map.setClickable(false);
            fab3_map.setClickable(false);
            isFabOpen = false;
        } else {
            fab1_map.startAnimation(fab_open);
            fab2_map.startAnimation(fab_open);
            fab3_map.startAnimation(fab_open);
            fab1_map.setClickable(true);
            fab2_map.setClickable(true);
            fab3_map.setClickable(true);
            isFabOpen = true;
        }
    }

    private void findAttractionOnMap() {
        LatLng location = new LatLng(Double.parseDouble(mapy), Double.parseDouble(mapx));

        MarkerOptions markerOptions = new MarkerOptions()
                .title(arrayList.get(position).getTitle())
                .snippet(arrayList.get(position).getAddr1())
                .position(location);
        googleMap.addMarker(markerOptions);
        googleMap.animateCamera(CameraUpdateFactory.newLatLngZoom(location, 16));
        googleMap.getUiSettings().setZoomControlsEnabled(true);
    }

    @Override
    public void onBackPressed() {
        super.onBackPressed();
        finish();
    }
}