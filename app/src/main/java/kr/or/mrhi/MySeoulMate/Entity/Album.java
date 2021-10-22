package kr.or.mrhi.MySeoulMate.Entity;

import java.io.Serializable;

public class Album implements Serializable {

    private String title;
    private String content;
    private String currentDate;
    private String image;

    public Album() {

    }

    public Album(String title, String content, String currentDate) {
        this.title = title;
        this.content = content;
        this.currentDate = currentDate;
    }

    public Album(String title, String content, String currentDate, String image) {
        this.title = title;
        this.content = content;
        this.currentDate = currentDate;
        this.image = image;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public String getContent() {
        return content;
    }

    public void setContent(String content) {
        this.content = content;
    }

    public String getCurrentDate() {
        return currentDate;
    }

    public void setCurrentDate(String currentDate) {
        this.currentDate = currentDate;
    }

    public String getImage() {
        return image;
    }

    public void setImage(String image) {
        this.image = image;
    }
}



