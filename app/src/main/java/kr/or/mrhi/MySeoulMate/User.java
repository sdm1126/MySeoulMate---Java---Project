package kr.or.mrhi.MySeoulMate;

import java.io.Serializable;

public class User implements Serializable {
    private String userId;
    private String userName;
    private String userImage;

    public User(String userId, String userName, String userImage) {
        this.userId = userId;
        this.userName = userName;
        this.userImage = userImage;
    }

    public String getUserId() {
        return userId;
    }

    public void setUserId(String userId) {
        this.userId = userId;
    }

    public String getUserName() {
        return userName;
    }

    public void setUserName(String userName) {
        this.userName = userName;
    }

    public String getUserImage() {
        return userImage;
    }

    public void setUserImage(String userImage) {
        this.userImage = userImage;
    }
}
