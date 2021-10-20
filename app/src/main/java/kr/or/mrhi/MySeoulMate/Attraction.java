package kr.or.mrhi.MySeoulMate;

import java.io.Serializable;

public class Attraction implements Serializable {
    // 기본 정보
    private String addr1;
    private String addr2;
    private String areacode;
    private String contentid;
    private String contenttypeid;
    private String firstimage;
    private String mapx;
    private String mapy;
    private String readcount;
    private String sigungucode;
    private String tel;
    private String title;
    
    // 추가 정보
    private String expguide; // 체험 안내
    private String infocenter; // 문의 및 안내
    private String restdate; // 쉬는 날
    private String usetime; // 이용 시간

    public Attraction() {
    }

    // Location
    public Attraction(String addr1, String contentid, String contenttypeid, String mapx, String mapy, String title) {
        this.addr1 = addr1;
        this.contentid = contentid;
        this.contenttypeid = contenttypeid;
        this.mapx = mapx;
        this.mapy = mapy;
        this.title = title;
    }

    // Like
    public Attraction(String addr1, String contentid, String firstimage, String mapx, String mapy, String readcount, String title) {
        this.addr1 = addr1;
        this.contentid = contentid;
        this.firstimage = firstimage;
        this.mapx = mapx;
        this.mapy = mapy;
        this.readcount = readcount;
        this.title = title;
    }

    // Area
    public Attraction(String addr1, String addr2, String areacode, String contentid, String contenttypeid, String firstimage, String mapx, String mapy, String readcount, String sigungucode, String tel, String title) {
        this.addr1 = addr1;
        this.addr2 = addr2;
        this.areacode = areacode;
        this.contentid = contentid;
        this.contenttypeid = contenttypeid;
        this.firstimage = firstimage;
        this.mapx = mapx;
        this.mapy = mapy;
        this.readcount = readcount;
        this.sigungucode = sigungucode;
        this.tel = tel;
        this.title = title;
    }

    // 기본 정보
    public String getAddr1() {
        return addr1;
    }

    public void setAddr1(String addr1) {
        this.addr1 = addr1;
    }

    public String getAddr2() {
        return addr2;
    }

    public void setAddr2(String addr2) {
        this.addr2 = addr2;
    }

    public String getAreacode() {
        return areacode;
    }

    public void setAreacode(String areacode) {
        this.areacode = areacode;
    }

    public String getContentid() {
        return contentid;
    }

    public void setContentid(String contentid) {
        this.contentid = contentid;
    }

    public String getContenttypeid() {
        return contenttypeid;
    }

    public void setContenttypeid(String contenttypeid) {
        this.contenttypeid = contenttypeid;
    }

    public String getFirstimage() {
        return firstimage;
    }

    public void setFirstimage(String firstimage) {
        this.firstimage = firstimage;
    }

    public String getMapx() {
        return mapx;
    }

    public void setMapx(String mapx) {
        this.mapx = mapx;
    }

    public String getMapy() {
        return mapy;
    }

    public void setMapy(String mapy) {
        this.mapy = mapy;
    }

    public String getReadcount() {
        return readcount;
    }

    public void setReadcount(String readcount) {
        this.readcount = readcount;
    }

    public String getSigungucode() {
        return sigungucode;
    }

    public void setSigungucode(String sigungucode) {
        this.sigungucode = sigungucode;
    }

    public String getTel() {
        return tel;
    }

    public void setTel(String tel) {
        this.tel = tel;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    // 추가 정보
    public String getExpguide() {
        return expguide;
    }

    public void setExpguide(String expguide) {
        this.expguide = expguide;
    }

    public String getInfocenter() {
        return infocenter;
    }

    public void setInfocenter(String infocenter) {
        this.infocenter = infocenter;
    }

    public String getRestdate() {
        return restdate;
    }

    public void setRestdate(String restdate) {
        this.restdate = restdate;
    }

    public String getUsetime() {
        return usetime;
    }

    public void setUsetime(String usetime) {
        this.usetime = usetime;
    }
}
