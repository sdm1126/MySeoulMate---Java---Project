package kr.or.mrhi.MySeoulMate.Entity;

import java.io.Serializable;

public class Album implements Serializable {

    private String albumTitle;
    private String albumContent;
    private String currentDate;
    private String albumImage;

    public Album() {

    }

    public Album(String albumTitle, String albumContent, String currentDate) {
        this.albumTitle = albumTitle;
        this.albumContent = albumContent;
        this.currentDate = currentDate;
    }

    public Album(String albumTitle, String albumContent, String currentDate, String albumImage) {
        this.albumTitle = albumTitle;
        this.albumContent = albumContent;
        this.currentDate = currentDate;
        this.albumImage = albumImage;
    }

    public String getAlbumTitle() {
        return albumTitle;
    }

    public void setAlbumTitle(String albumTitle) {
        this.albumTitle = albumTitle;
    }

    public String getAlbumContent() {
        return albumContent;
    }

    public void setAlbumContent(String albumContent) {
        this.albumContent = albumContent;
    }

    public String getCurrentDate() {
        return currentDate;
    }

    public void setCurrentDate(String currentDate) {
        this.currentDate = currentDate;
    }

    public String getAlbumImage() {
        return albumImage;
    }

    public void setAlbumImage(String albumImage) {
        this.albumImage = albumImage;
    }
}



