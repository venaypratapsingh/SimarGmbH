# 🔐 Login Credentials for Your Attendance System

## ✅ Default Admin Credentials

**Email:** `admin@simargmbh.com`  
**Password:** `password` (default - change this after first login!)

---

## 🚨 Important: Run Migrations & Seeders First!

**Before you can login, you need to:**

### On Render.com:

1. **Go to Render Dashboard** → Your Service → **Shell** tab
2. **Run migrations first:**
   ```bash
   php artisan migrate --force
   ```
3. **Then run seeder to create admin user:**
   ```bash
   php artisan db:seed --force
   ```

**This will create the admin user with these credentials!**

---

## 🔑 Login Details

After running migrations and seeders:

- **Email:** `admin@simargmbh.com`
- **Password:** `password`
- **Login URL:** `https://simargmbh-1.onrender.com/login` or `/admin`

---

## 🔒 Change Default Password

**IMPORTANT:** Change the password after first login for security!

### Option 1: Via App
1. Login with default credentials
2. Go to your profile/settings
3. Change password

### Option 2: Via Shell (Reset Password)
1. **Render Dashboard** → Shell tab
2. **Run:**
   ```bash
   php artisan tinker
   >>> $user = \App\Models\User::where('email', 'admin@simargmbh.com')->first();
   >>> $user->password = \Illuminate\Support\Facades\Hash::make('your-new-password');
   >>> $user->save();
   >>> exit
   ```

---

## 🆘 Can't Login?

### Issue 1: User doesn't exist
**Solution:** Run migrations and seeders (see above)

### Issue 2: Wrong password
**Solution:** Reset password via Shell (see above)

### Issue 3: Email doesn't work
**Solution:** Create new user via Shell:
```bash
php artisan tinker
>>> $user = \App\Models\User::create([
...     'name' => 'Admin',
...     'email' => 'admin@simargmbh.com',
...     'password' => \Illuminate\Support\Facades\Hash::make('password')
... ]);
>>> $role = \App\Models\Role::where('slug', 'admin')->first();
>>> $user->roles()->sync($role->id);
>>> exit
```

---

## 📋 Quick Checklist

- [ ] Migrations run (`php artisan migrate --force`)
- [ ] Seeder run (`php artisan db:seed --force`)
- [ ] Try login with: `admin@simargmbh.com` / `password`
- [ ] Change password after first login

---

**🎯 Run migrations and seeders first, then use: Email: `admin@simargmbh.com` Password: `password`**
