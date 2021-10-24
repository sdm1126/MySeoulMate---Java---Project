package kr.or.mrhi.MySeoulMate.AreaFragment;

import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.text.method.ScrollingMovementMethod;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AlertDialog;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.bumptech.glide.Glide;

import org.xmlpull.v1.XmlPullParser;
import org.xmlpull.v1.XmlPullParserFactory;

import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.URL;
import java.util.ArrayList;

import kr.or.mrhi.MySeoulMate.Activity.MainActivity;
import kr.or.mrhi.MySeoulMate.Activity.MapActivity;
import kr.or.mrhi.MySeoulMate.Adapter.AreaAdapter;
import kr.or.mrhi.MySeoulMate.Entity.Attraction;
import kr.or.mrhi.MySeoulMate.R;

// 동남(강남구, 강동구, 서초구, 송파구)
public class SouthEastFragment extends Fragment {

    // widget
    private RecyclerView rv_fragment_area;

    // data
    private XmlPullParser xpp;
    private Thread thread;
    private AreaAdapter areaAdapter;
    private ArrayList<Attraction> arrayList = new ArrayList<>();
    private Attraction attraction;

    // interface
    private AreaAdapter.OnMapIconClickListener onMapIconClickListener;
    private AreaAdapter.OnImageIconClickListener onImageIconClickListener;

    public static SouthEastFragment getInstance() {
        SouthEastFragment southEastFragment = new SouthEastFragment();
        return southEastFragment;
    }

    @Override
    public void onAttach(@NonNull Context context) {
        super.onAttach(context);

        // 데이터 생성
        thread = new Thread() {
            @Override
            public void run() {
                arrayList.clear();
                getXmlData("12", "1"); // 강남구(sigungucode: 1)
                getXmlData("12", "2"); // 강동구(sigungucode: 2)
                getXmlData("12", "15"); // 서초구(sigungucode: 15)
                getXmlData("12", "18"); // 송파구(sigungucode: 18)
            }
        };
        thread.start();
        try {
            thread.join();
        } catch (InterruptedException e) {
            Log.e("확인", e.toString());
        }

        Log.d("확인", "SouthEastFragment_" + arrayList.size());
        Log.d("확인", "SouthEastFragment_onAttach()");
    }

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_area, container, false);
        rv_fragment_area = view.findViewById(R.id.rv_fragment_area);

        Log.d("확인", "SouthEastFragment_" + arrayList.size());
        Log.d("확인", "SouthEastFragment_onCreateView()");
        return view;
    }

    @Override
    public void onResume() {
        super.onResume();

        // 지도 열기 아이콘 클릭 시,
        onMapIconClickListener = new AreaAdapter.OnMapIconClickListener() {
            @Override
            public void onMapIconClick(int position) {
                Intent intent = new Intent(getActivity(), MapActivity.class);
                intent.putExtra("arrayList", arrayList);
                intent.putExtra("position", position);
                startActivity(intent);
            }
        };

        // 이미지 아이콘 클릭 시,
        onImageIconClickListener = new AreaAdapter.OnImageIconClickListener() {
            @Override
            public void onImageIconClick(int position) {
                // Dialog 생성
                View view = View.inflate(getContext(), R.layout.dialog_area, null);
                AlertDialog.Builder builder = new AlertDialog.Builder(getContext());
                AlertDialog alertDialog = builder.create();
                alertDialog.setView(view);
                alertDialog.show();

                // Dialog findViewById()
                TextView tv_title_dialog_area = view.findViewById(R.id.tv_title_dialog_area);
                ImageView iv_image_dialog_area = view.findViewById(R.id.iv_image_dialog_area);
                TextView tv_content_dialog_area = view.findViewById(R.id.tv_content_dialog_area);
                Button btn_dialog_area = view.findViewById(R.id.btn_dialog_area);

                // Dialog 내용 세팅
                // 1. 제목
                tv_title_dialog_area.setText(arrayList.get(position).getTitle());

                // 2. 사진
                if(arrayList.get(position).getFirstimage() != null) {
                    Glide.with(getContext()).load(arrayList.get(position).getFirstimage()).into(iv_image_dialog_area);
                } else {
                    iv_image_dialog_area.setImageResource(R.drawable.ic_no_image);
                }

                // 3. 상세 내용
                thread = new Thread() {
                    @Override
                    public void run() {
                        getAdditionalXmlData(arrayList.get(position).getContentid(), arrayList.get(position).getContenttypeid());
                    }
                };
                thread.start();
                try {
                    thread.join();
                } catch (InterruptedException e) {
                    Log.e("확인", e.toString());
                }

                StringBuffer text = new StringBuffer();
                String expguide = attraction.getExpguide();
                String infocenter = attraction.getInfocenter();
                String restdate = attraction.getRestdate();
                String usetime = attraction.getUsetime();

                if(expguide != null) {
                    if(expguide.contains("<br>") || expguide.contains("<br />")) {
                        expguide = expguide.replace("<br>", "");
                        expguide = expguide.replace("<br />", "");
                    }
                    text.append("체험안내> " + "\n" + expguide + "\n\n");
                }
                if(infocenter != null) {
                    if(infocenter.contains("<br>") || infocenter.contains("<br />")) {
                        infocenter = infocenter.replace("<br>", "");
                        infocenter = infocenter.replace("<br />", "");
                    }
                    text.append("문의 및 안내> " + "\n" + infocenter + "\n\n");
                }
                if(restdate != null) {
                    if(restdate.contains("<br>") || restdate.contains("<br />")) {
                        restdate = restdate.replace("<br>", "");
                        restdate = restdate.replace("<br />", "");
                    }
                    text.append("쉬는 날> " + "\n" + restdate + "\n\n");
                }
                if(usetime != null) {
                    if(usetime.contains("<br>") || usetime.contains("<br />")) {
                        usetime = usetime.replace("<br>", "");
                        usetime = usetime.replace("<br />", "");
                    }
                    text.append("이용시간> " + "\n" + usetime + "\n\n");
                }

                tv_content_dialog_area.setMovementMethod(new ScrollingMovementMethod());
                tv_content_dialog_area.setText(text);

                btn_dialog_area.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View view) {
                        alertDialog.dismiss();
                    }
                });
            }
        };

        // Adapter & LinearLayoutManager 생성
        areaAdapter = new AreaAdapter(getContext(), arrayList);
        LinearLayoutManager linearLayoutManager = new LinearLayoutManager(getContext());

        // Adapter & LinearLayoutManager 적용
        areaAdapter.setOnMapIconClickListener(onMapIconClickListener);
        areaAdapter.setOnImageIconClickListener(onImageIconClickListener);
        rv_fragment_area.setLayoutManager(linearLayoutManager);
        rv_fragment_area.setAdapter(areaAdapter);
        areaAdapter.notifyDataSetChanged();

        Log.d("확인", "SouthEastFragment_" + arrayList.size());
        Log.d("확인", "SouthEastFragment_onResume()");
    }

    // 기본 정보
    private void getXmlData(String contenttypeid, String sigungucode) {
        Attraction attraction = null;
        String tag = null;

        String queryURL =
                "http://api.visitkorea.or.kr/openapi/service/rest/KorService/areaBasedList?serviceKey="
                + MainActivity.KEY
                + "&numOfRows=1000&MobileApp=" + MainActivity.APP_NAME + "&MobileOS=ETC&arrange=B&contentTypeId=" + contenttypeid + "&areaCode=1&sigunguCode=" + sigungucode;

        try {
            URL url = new URL(queryURL);
            InputStream is = url.openStream();

            XmlPullParserFactory factory = XmlPullParserFactory.newInstance();
            xpp = factory.newPullParser();
            xpp.setInput(new InputStreamReader(is, "UTF-8"));
            xpp.next();
            int eventType = xpp.getEventType();

            while (eventType != XmlPullParser.END_DOCUMENT) {
                switch (eventType) {
                    case XmlPullParser.START_DOCUMENT:
                        break;

                    case XmlPullParser.START_TAG:
                        tag = xpp.getName();

                        switch(tag) {
                            case "item":
                                attraction = new Attraction();
                                break;
                            case "addr1":
                                xpp.next();
                                attraction.setAddr1(xpp.getText());
                                break;
                            case "addr2":
                                xpp.next();
                                attraction.setAddr2(xpp.getText());
                                break;
                            case "areacode":
                                xpp.next();
                                attraction.setAreacode(xpp.getText());
                                break;
                            case "contentid":
                                xpp.next();
                                attraction.setContentid(xpp.getText());
                                break;
                            case "contenttypeid":
                                xpp.next();
                                attraction.setContenttypeid(xpp.getText());
                                break;
                            case "firstimage":
                                xpp.next();
                                attraction.setFirstimage(xpp.getText());
                                break;
                            case "mapx":
                                xpp.next();
                                attraction.setMapx(xpp.getText());
                                break;
                            case "mapy":
                                xpp.next();
                                attraction.setMapy(xpp.getText());
                                break;
                            case "readcount":
                                xpp.next();
                                attraction.setReadcount(xpp.getText());
                                break;
                            case "sigungucode":
                                xpp.next();
                                attraction.setSigungucode(xpp.getText());
                                break;
                            case "tel":
                                xpp.next();
                                attraction.setTel(xpp.getText());
                                break;
                            case "title":
                                xpp.next();
                                attraction.setTitle(xpp.getText());
                                break;
                        }

                    case XmlPullParser.TEXT:
                        break;

                    case XmlPullParser.END_TAG:
                        tag = xpp.getName();
                        if(tag.equals("item")) {
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

    // 추가 정보
    private void getAdditionalXmlData(String contentid, String contenttypeid) {
        String tag = null;

        String queryURL =
                "http://api.visitkorea.or.kr/openapi/service/rest/KorService/detailIntro?serviceKey="
                        + MainActivity.KEY
                        + "&numOfRows=1&MobileOS=ETC&MobileApp=" + MainActivity.APP_NAME + "&contentId=" + contentid + "&contentTypeId=" + contenttypeid;

        try {
            URL url = new URL(queryURL);
            InputStream is = url.openStream();

            XmlPullParserFactory factory = XmlPullParserFactory.newInstance();
            xpp = factory.newPullParser();
            xpp.setInput(new InputStreamReader(is, "UTF-8"));
            xpp.next();
            int eventType = xpp.getEventType();

            while (eventType != XmlPullParser.END_DOCUMENT) {
                switch (eventType) {
                    case XmlPullParser.START_DOCUMENT:
                        break;

                    case XmlPullParser.START_TAG:
                        tag = xpp.getName();

                        switch(tag) {
                            case "item":
                                attraction = new Attraction();
                                break;
                            case "expguide":
                                xpp.next();
                                attraction.setExpguide(xpp.getText());
                                break;
                            case "infocenter":
                                xpp.next();
                                attraction.setInfocenter(xpp.getText());
                                break;
                            case "restdate":
                                xpp.next();
                                attraction.setRestdate(xpp.getText());
                                break;
                            case "usetime":
                                xpp.next();
                                attraction.setUsetime(xpp.getText());
                                break;
                        }

                    case XmlPullParser.TEXT:
                        break;

                    case XmlPullParser.END_TAG:
                        break;

                }
                eventType = xpp.next();
            }

        } catch (Exception e) {
            Log.e("확인", e.toString());
        }
    }
}