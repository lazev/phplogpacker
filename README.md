# PHPLogPacker

A lightweight PHP utility to automatically compress and archive files when they reach a defined size threshold.

## ✨ Features

- ✅ Compresses files >50MB (default) using 7Zip or fallback to Zip  
- ✅ Recursive directory scanning (includes subfolders)  
- ✅ Configurable retention policy and compression settings  
- ✅ Zero dependencies – pure PHP CLI tool  

## 🚀 Usage

### Basic Command

```bash
php logpacker.php /target/directory/
```

### How It Works

- Scans `/target/directory/` and all subfolders  
- Compresses files exceeding `max_file_size_MB` (default: 50MB)  
- Maintains `num_files_archived` versions (default: 5)  
- Oldest archives are automatically deleted  

## ⚙️ Configuration (optional)

To customize, create a `logpacker.ini` file in the script's root directory:

```ini
; logpacker.ini
num_files_archived = 5   ; Number of archived versions to keep
max_file_size_MB   = 50  ; Minimum file size to trigger compression (in MB)
days_last_change   = 0   ; [TODO] Archive files modified X days ago
days_from_creation = 0   ; [TODO] Archive files created X days ago
archive_extension  = 7z  ; Compression format (7z or zip)
```

## 🔄 Archive Rotation Example

### Original structure:
```
error.log (60MB)
```

### After first run:
```
error.log.1.7z (compressed)
```

### After subsequent runs:
```
error.log.1.7z → error.log.2.7z
error.log.2.7z → error.log.3.7z
...
error.log.5.7z → deleted (oldest archive)
```

## 📦 Compression Methods

- **7Zip** (default, if installed on system)  
- **Zip** (fallback if 7Zip unavailable)  

## 📜 License

Open-source under the **MIT License**.
