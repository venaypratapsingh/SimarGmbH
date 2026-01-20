# 🔧 Railway Deployment Troubleshooting

## ✅ Your App is Running!

If you see:
```
INFO  Server running on [http://0.0.0.0:8000].
Press Ctrl+C to stop the server
```

And Railway shows **"online"** - **Your app is working!** 🎉

---

## 🔍 Check Your Railway URL

1. **In Railway Dashboard:**
   - Click on your service
   - Look for **"Settings"** tab
   - Find **"Generate Domain"** button (if not done yet)
   - Or check the **"Domains"** section

2. **Your URL will be:**
   - `https://your-app-name.up.railway.app`
   - Or a custom domain you set up

3. **Click the URL** or copy it and open in browser

---

## ⚠️ If You See Port 8000 in Logs

The message "Server running on [http://0.0.0.0:8000]" is **normal**. However:

### Fix Start Command (Important!)

1. Go to Railway → Your Service → **"Settings"** tab
2. Find **"Start Command"** section
3. Make sure it uses `$PORT` (Railway's dynamic port):

**Correct:**
```
php artisan serve --host=0.0.0.0 --port=$PORT
```

**Wrong (don't use this):**
```
php artisan serve --host=0.0.0.0 --port=8000
```

4. **Save** and Railway will restart automatically

---

## 🚀 Quick Fixes

### Issue 1: Can't Access URL

**Solution:**
- Make sure you generated a domain in Railway Settings
- Check the URL is correct
- Try clicking the URL directly from Railway dashboard

### Issue 2: 500 Internal Server Error

**Check:**
1. APP_KEY is set in Variables tab
2. Database credentials are correct
3. Run migrations (see below)

### Issue 3: Database Connection Error

**Verify:**
- Supabase project is active
- All database variables are set correctly:
  - `DB_HOST=db.gaceopxhzgdxjjbflozf.supabase.co`
  - `DB_PASSWORD=lA24fOnhlqTlQkHv`
  - `DB_SSLMODE=require`

---

## 📋 Next Steps After Deployment

### 1. Run Database Migrations

**Via Railway Shell:**
- Go to your service
- Click **"View Logs"** or use Railway CLI
- Run:
  ```bash
  php artisan migrate --force
  ```

### 2. Update APP_URL

1. Copy your Railway URL (e.g., `https://your-app.up.railway.app`)
2. Go to **Variables** tab
3. Update `APP_URL` with your actual URL
4. Service will auto-restart

### 3. Verify Everything Works

- Visit your Railway URL
- Check if app loads
- Test login/functionality

---

## ✅ Status Check

Your app shows:
- ✅ "Server running on [http://0.0.0.0:8000]" - **App is running**
- ✅ Railway shows "online" - **Container is active**

**This means your app is deployed successfully!** 

Just make sure:
- [ ] Start command uses `$PORT` (not hardcoded 8000)
- [ ] You have a domain/URL in Railway Settings
- [ ] Environment variables are set correctly
- [ ] APP_KEY is configured
- [ ] Migrations are run

---

## 🔗 How to Find Your Railway URL

1. **Railway Dashboard** → Your Project
2. Click on your service
3. Look for:
   - **"Settings"** → **"Generate Domain"** (if not generated)
   - **"Domains"** section (shows your URL)
   - Or **"Settings"** → Scroll down for public URL

4. **Click the URL** to open your app!

---

## 🆘 Still Having Issues?

1. **Check Logs:**
   - Railway Dashboard → Your Service → **"Logs"** tab
   - Look for errors (red text)

2. **Verify Environment Variables:**
   - All variables set correctly
   - APP_KEY is present
   - Database credentials are correct

3. **Restart Service:**
   - Settings → **"Redeploy"** or **"Restart"**

---

**🎉 If Railway shows "online" - your app is live! Just find and visit your URL!**
