package kr.or.mrhi.MySeoulMate;

import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;
import android.util.Log;

import androidx.annotation.Nullable;

import java.util.ArrayList;

public class MySeoulMateDBHelper extends SQLiteOpenHelper {
    public static MySeoulMateDBHelper mySeoulMateDBHelper;
    private Context context;
    private Attraction attraction;

    public MySeoulMateDBHelper(Context context) {
        super(context, "MySeoulMate", null, 1);
        this.context = context;
    }

    public static MySeoulMateDBHelper getInstance(Context context) {
        mySeoulMateDBHelper = new MySeoulMateDBHelper(context);
        return mySeoulMateDBHelper;
    }

    @Override
    public void onCreate(SQLiteDatabase sqLiteDatabase) {
        sqLiteDatabase.execSQL("CREATE TABLE userTBL(" +
                "userId TEXT NOT NULL PRIMARY KEY," +
                "userName TEXT NOT NULL," +
                "userImage TEXT NOT NULL);");
    }

    @Override
    public void onUpgrade(SQLiteDatabase sqLiteDatabase, int oldVersion, int newVersion) {
        sqLiteDatabase.execSQL("DROP TABLE IF EXISTS userTBL");
        onCreate(sqLiteDatabase);
    }

    public void insertUser(User user) {
        SQLiteDatabase sqLiteDatabase = null;
        try {
            sqLiteDatabase = this.getWritableDatabase();
            sqLiteDatabase.execSQL("INSERT OR REPLACE INTO userTBL VALUES ('" +
                    user.getUserId() + "', '" +
                    user.getUserName() + "', '" +
                    user.getUserImage() + "');");
            Log.e("확인", "MySeoulMateDBHelper_insertUser() 성공");
        } catch (Exception e) {
            Log.e("확인", "MySeoulMateDBHelper_insertUser() 실패 " + e.toString());
        } finally {
            if(sqLiteDatabase != null) {
                sqLiteDatabase.close();
            }
        }
    }

    public void deleteUser(String userId) {
        SQLiteDatabase sqLiteDatabase = null;
        try {
            sqLiteDatabase = this.getWritableDatabase();
            sqLiteDatabase.execSQL("DELETE FROM userTBL WHERE userId = " + "'" + userId + "'");
            Log.e("확인", "MySeoulMateDBHelper_deleteUser() 성공");
        } catch (Exception e) {
            Log.e("확인", "MySeoulMateDBHelper_deleteUser() 실패 " + e.toString());
        } finally {
            if(sqLiteDatabase != null) {
                sqLiteDatabase.close();
            }
        }
    }

    public User loadUser() {
        User user = null;
        SQLiteDatabase sqLiteDatabase = null;
        Cursor cursor = null;
        try {
            sqLiteDatabase = this.getReadableDatabase();
            cursor = sqLiteDatabase.rawQuery("SELECT * FROM userTBL;", null);
            while(cursor.moveToNext()) {
                user = new User(cursor.getString(0), cursor.getString(1), cursor.getString(2));
            }
            Log.e("확인", "MySeoulMateDBHelper_loadUser() 성공");
        } catch (Exception e) {
            Log.e("확인", "MySeoulMateDBHelper_loadUser() 실패");
        } finally {
            if(cursor != null) {
                cursor.close();
            }
            if(sqLiteDatabase != null) {
                sqLiteDatabase.close();
            }
        }
        return user;
    }

    public void createLike(User user) {
        SQLiteDatabase sqLiteDatabase = null;
        try {
            sqLiteDatabase = this.getWritableDatabase();
            sqLiteDatabase.execSQL("CREATE TABLE IF NOT EXISTS " + user.getUserId() + "LikeTBL(addr1 TEXT, contentid TEXT NOT NULL PRIMARY KEY, firstimage TEXT, mapx REAL, mapy REAL, readcount TEXT, title TEXT);");
            Log.e("확인", "MySeoulMateDBHelper_createLike() 성공");
        } catch (Exception e) {
            Log.e("확인", "MySeoulMateDBHelper_createLike() 실패" + e.toString());
        } finally {
            if(sqLiteDatabase != null) {
                sqLiteDatabase.close();
            }
        }
    }

    public void insertLike(String userId, Attraction attraction) {
        SQLiteDatabase sqLiteDatabase = null;
        try {
            sqLiteDatabase = this.getWritableDatabase();
            sqLiteDatabase.execSQL("INSERT INTO " + userId + "LikeTBL (addr1, contentid, firstimage, mapx, mapy, readcount, title) VALUES ('"
                    + attraction.getAddr1() + "','"
                    + attraction.getContentid() + "','"
                    + attraction.getFirstimage() + "','"
                    + attraction.getMapx() + "','"
                    + attraction.getMapy() + "','"
                    + attraction.getReadcount() + "','"
                    + attraction.getTitle() + "');");
            Log.e("확인", "MySeoulMateDBHelper_insertLike() 성공");
        } catch (Exception e) {
            Log.e("확인", "MySeoulMateDBHelper_insertLike() 실패 " + e.toString());
        } finally {
            if(sqLiteDatabase != null) {
                sqLiteDatabase.close();
            }
        }
    }

    public void deleteLike(String userId, Attraction attraction) {
        SQLiteDatabase sqLiteDatabase = null;
        try {
            sqLiteDatabase = this.getWritableDatabase();
            sqLiteDatabase.execSQL("DELETE FROM " + userId + "LikeTBL WHERE contentid = '" + attraction.getContentid() + "';");
            Log.e("확인", "MySeoulMateDBHelper_deleteLike() 성공");
        } catch (Exception e) {
            Log.e("확인", "MySeoulMateDBHelper_deleteLike() 실패 " + e.toString());
        } finally {
            if(sqLiteDatabase != null) {
                sqLiteDatabase.close();
            }
        }
    }

    // 연결 끊기 시 좋아요 테이블 삭제 
    public void dropLike(String userId) {
        SQLiteDatabase sqLiteDatabase = null;
        try {
            sqLiteDatabase = this.getWritableDatabase();
            sqLiteDatabase.execSQL("DROP TABLE IF EXISTS " + userId + "LikeTBL");
            Log.e("확인", "MySeoulMateDBHelper_dropLike() 성공");
        } catch (Exception e) {
            Log.e("확인", "MySeoulMateDBHelper_dropLike() 실패 " + e.toString());
        } finally {
            if(sqLiteDatabase != null) {
                sqLiteDatabase.close();
            }
        }
    }

    public ArrayList<Attraction> loadLike(String userId) {
        ArrayList<Attraction> arrayList = new ArrayList<>();
        SQLiteDatabase sqLiteDatabase = null;
        Cursor cursor = null;
        try {
            sqLiteDatabase = this.getReadableDatabase();
            cursor = sqLiteDatabase.rawQuery("SELECT addr1, contentid, firstimage, mapx, mapy, readcount, title FROM " + userId + "LikeTBL;", null);
            while(cursor.moveToNext()) {
                arrayList.add(new Attraction(cursor.getString(0), cursor.getString(1), cursor.getString(2), cursor.getString(3), cursor.getString(4), cursor.getString(5), cursor.getString(6)));
            }
            Log.e("확인", "MySeoulMateDBHelper_loadLike() 성공");
        } catch (Exception e) {
            Log.e("확인", "MySeoulMateDBHelper_loadLike() 실패 " + e.toString());
        } finally {
            if(cursor != null) {
                cursor.close();
            }
            if(sqLiteDatabase != null) {
                sqLiteDatabase.close();
            }
        }
        return arrayList;
    }
}
