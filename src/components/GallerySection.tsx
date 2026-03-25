import React, { useState, useEffect } from 'react';
import { Heart, Eye, Calendar, User, Filter, Grid, List, Download, Share2, Maximize2 } from 'lucide-react';

interface GalleryItem {
  id: number;
  title: string;
  description: string;
  file_url: string;
  file_type: 'image' | 'video';
  file_size: number;
  dimensions: string;
  category: string | null;
  tags: string[];
  is_featured: boolean;
  status: string;
  uploaded_by_first_name: string;
  uploaded_by_last_name: string;
  created_at: string;
}

interface GallerySectionProps {
  category?: string;
  limit?: number;
  showFilters?: boolean;
}

const GallerySection: React.FC<GallerySectionProps> = ({ 
  category, 
  limit = 12, 
  showFilters = true 
}) => {
  const [items, setItems] = useState<GalleryItem[]>([]);
  const [filteredItems, setFilteredItems] = useState<GalleryItem[]>([]);
  const [categories, setCategories] = useState<string[]>([]);
  const [selectedCategory, setSelectedCategory] = useState<string>(category || 'all');
  const [viewMode, setViewMode] = useState<'grid' | 'list'>('grid');
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [selectedItem, setSelectedItem] = useState<GalleryItem | null>(null);

  useEffect(() => {
    fetchGallery();
    fetchCategories();
  }, [limit]);

  useEffect(() => {
    filterItems();
  }, [items, selectedCategory]);

  const fetchGallery = async () => {
    try {
      setLoading(true);
      const url = category 
        ? `http://localhost:5000/api/gallery?status=published&category=${category}&limit=${limit}`
        : `http://localhost:5000/api/gallery?status=published&limit=${limit}`;
      
      const response = await fetch(url);
      const data = await response.json();
      
      if (data.success) {
        setItems(data.data.items);
      } else {
        setError('Failed to load gallery items');
      }
    } catch (error) {
      setError('Network error loading gallery');
      console.error('Error fetching gallery:', error);
    } finally {
      setLoading(false);
    }
  };

  const fetchCategories = async () => {
    try {
      const response = await fetch('http://localhost:5000/api/gallery');
      const data = await response.json();
      
      if (data.success) {
        const uniqueCategories = Array.from(
          new Set(data.data.items.map((item: GalleryItem) => item.category).filter(Boolean)
        ) as string[];
        setCategories(uniqueCategories);
      }
    } catch (error) {
      console.error('Error fetching categories:', error);
    }
  };

  const filterItems = () => {
    if (selectedCategory === 'all') {
      setFilteredItems(items);
    } else {
      setFilteredItems(items.filter(item => item.category === selectedCategory));
    }
  };

  const formatFileSize = (bytes: number) => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  };

  const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric'
    });
  };

  const handleDownload = (item: GalleryItem) => {
    const link = document.createElement('a');
    link.href = `http://localhost:5000${item.file_url}`;
    link.download = item.title;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  };

  const handleShare = async (item: GalleryItem) => {
    if (navigator.share) {
      try {
        await navigator.share({
          title: item.title,
          text: item.description,
          url: `http://localhost:5000${item.file_url}`
        });
      } catch (error) {
        console.log('Error sharing:', error);
      }
    } else {
      // Fallback - copy to clipboard
      navigator.clipboard.writeText(`http://localhost:5000${item.file_url}`);
    }
  };

  if (loading) {
    return (
      <div className="space-y-6">
        {showFilters && (
          <div className="flex items-center justify-between animate-pulse">
            <div className="h-10 bg-gold/20 rounded w-48"></div>
            <div className="flex gap-2">
              <div className="h-10 bg-gold/20 rounded w-20"></div>
              <div className="h-10 bg-gold/20 rounded w-20"></div>
            </div>
          </div>
        )}
        <div className={`grid ${viewMode === 'grid' ? 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4' : 'grid-cols-1'} gap-6`}>
          {[...Array(limit)].map((_, i) => (
            <div key={i} className="glassmorphism-enhanced rounded-2xl overflow-hidden animate-pulse">
              <div className="aspect-square bg-gold/20"></div>
              <div className="p-4 space-y-2">
                <div className="h-4 bg-gold/20 rounded w-3/4"></div>
                <div className="h-3 bg-gold/20 rounded w-full"></div>
              </div>
            </div>
          ))}
        </div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="glassmorphism-enhanced rounded-2xl p-8 text-center">
        <h3 className="text-xl font-semibold text-foreground mb-2">Unable to Load Gallery</h3>
        <p className="text-muted-foreground mb-4">{error}</p>
        <button 
          onClick={fetchGallery}
          className="px-6 py-2 bg-gradient-gold text-primary font-semibold rounded-lg hover:opacity-90 transition"
        >
          Try Again
        </button>
      </div>
    );
  }

  return (
    <div className="space-y-6">
      {/* Header and Filters */}
      <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <h2 className="font-blackadder text-3xl font-bold text-foreground text-gradient-gold">
          Church Gallery
        </h2>
        
        {showFilters && (
          <div className="flex items-center gap-4">
            {/* Category Filter */}
            <select 
              value={selectedCategory}
              onChange={(e) => setSelectedCategory(e.target.value)}
              className="px-4 py-2 bg-gold/10 border border-gold/30 rounded-lg text-foreground focus:outline-none focus:ring-2 focus:ring-gold/50"
            >
              <option value="all">All Categories</option>
              {categories.map((cat) => (
                <option key={cat} value={cat}>
                  {cat.charAt(0).toUpperCase() + cat.slice(1)}
                </option>
              ))}
            </select>

            {/* View Mode Toggle */}
            <div className="flex bg-gold/10 rounded-lg p-1">
              <button
                onClick={() => setViewMode('grid')}
                className={`p-2 rounded ${viewMode === 'grid' ? 'bg-gold text-primary' : 'text-foreground'}`}
              >
                <Grid className="w-4 h-4" />
              </button>
              <button
                onClick={() => setViewMode('list')}
                className={`p-2 rounded ${viewMode === 'list' ? 'bg-gold text-primary' : 'text-foreground'}`}
              >
                <List className="w-4 h-4" />
              </button>
            </div>
          </div>
        )}
      </div>

      {/* Gallery Grid/List */}
      <div className={`grid ${viewMode === 'grid' ? 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4' : 'grid-cols-1'} gap-6`}>
        {filteredItems.map((item) => (
          <div 
            key={item.id}
            className="glassmorphism-enhanced rounded-2xl overflow-hidden border border-white/10 shadow-gold-enhanced card-hover-3d group"
          >
            {/* Media Preview */}
            <div className="relative aspect-square overflow-hidden">
              {item.file_type === 'image' ? (
                <img 
                  src={`http://localhost:5000${item.file_url}`}
                  alt={item.title}
                  className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                />
              ) : (
                <div className="w-full h-full bg-gradient-to-br from-gold/20 to-navy/20 flex items-center justify-center">
                  <div className="text-center">
                    <div className="w-16 h-16 bg-gold/30 rounded-full flex items-center justify-center mx-auto mb-2">
                      <span className="text-2xl">▶️</span>
                    </div>
                    <p className="text-sm text-foreground">Video</p>
                  </div>
                </div>
              )}
              
              {/* Featured Badge */}
              {item.is_featured && (
                <div className="absolute top-3 left-3">
                  <span className="px-2 py-1 bg-gold text-primary text-xs font-semibold rounded-full">
                    Featured
                  </span>
                </div>
              )}

              {/* Action Buttons */}
              <div className="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity">
                <div className="flex flex-col gap-2">
                  <button
                    onClick={() => setSelectedItem(item)}
                    className="w-8 h-8 bg-black/50 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-black/70 transition"
                  >
                    <Maximize2 className="w-4 h-4" />
                  </button>
                  <button
                    onClick={() => handleShare(item)}
                    className="w-8 h-8 bg-black/50 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-black/70 transition"
                  >
                    <Share2 className="w-4 h-4" />
                  </button>
                </div>
              </div>
            </div>

            {/* Content */}
            <div className="p-4">
              <h3 className="font-semibold text-foreground mb-2 line-clamp-1 group-hover:text-gold transition">
                {item.title}
              </h3>
              
              <p className="text-sm text-muted-foreground mb-3 line-clamp-2">
                {item.description}
              </p>

              {/* Meta Information */}
              <div className="flex items-center justify-between text-xs text-muted-foreground mb-3">
                <span className="flex items-center gap-1">
                  <User className="w-3 h-3" />
                  {item.uploaded_by_first_name} {item.uploaded_by_last_name}
                </span>
                <span className="flex items-center gap-1">
                  <Calendar className="w-3 h-3" />
                  {formatDate(item.created_at)}
                </span>
              </div>

              {/* File Info */}
              <div className="flex items-center justify-between pt-3 border-t border-white/10">
                <div className="flex items-center gap-3 text-xs text-muted-foreground">
                  <span>{item.dimensions}</span>
                  <span>{formatFileSize(item.file_size)}</span>
                </div>
                <button
                  onClick={() => handleDownload(item)}
                  className="text-gold hover:text-gold/80 transition"
                >
                  <Download className="w-4 h-4" />
                </button>
              </div>
            </div>
          </div>
        ))}
      </div>

      {/* Load More Button */}
      {filteredItems.length > 0 && (
        <div className="text-center">
          <button className="inline-flex items-center px-8 py-3 bg-gradient-gold text-primary font-semibold rounded-lg shadow-gold-enhanced hover:scale-105 transition">
            Load More Items
          </button>
        </div>
      )}

      {/* Empty State */}
      {filteredItems.length === 0 && !loading && (
        <div className="glassmorphism-enhanced rounded-2xl p-12 text-center">
          <div className="w-16 h-16 bg-gold/20 rounded-full flex items-center justify-center mx-auto mb-4">
            <span className="text-2xl">🖼️</span>
          </div>
          <h3 className="text-xl font-semibold text-foreground mb-2">No Gallery Items</h3>
          <p className="text-muted-foreground">
            {selectedCategory !== 'all' 
              ? `No items found in ${selectedCategory} category.` 
              : 'No gallery items available at the moment.'
            }
          </p>
        </div>
      )}

      {/* Lightbox Modal */}
      {selectedItem && (
        <div 
          className="fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4"
          onClick={() => setSelectedItem(null)}
        >
          <div 
            className="max-w-4xl w-full max-h-full overflow-auto"
            onClick={(e) => e.stopPropagation()}
          >
            <div className="glassmorphism-enhanced rounded-2xl overflow-hidden">
              {/* Media */}
              <div className="relative">
                {selectedItem.file_type === 'image' ? (
                  <img 
                    src={`http://localhost:5000${selectedItem.file_url}`}
                    alt={selectedItem.title}
                    className="w-full h-auto max-h-[70vh] object-contain"
                  />
                ) : (
                  <div className="aspect-video bg-black flex items-center justify-center">
                    <video controls className="max-w-full max-h-[70vh]">
                      <source src={`http://localhost:5000${selectedItem.file_url}`} />
                      Your browser does not support the video tag.
                    </video>
                  </div>
                )}
                
                {/* Close Button */}
                <button
                  onClick={() => setSelectedItem(null)}
                  className="absolute top-4 right-4 w-10 h-10 bg-black/50 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-black/70 transition"
                >
                  ✕
                </button>
              </div>

              {/* Details */}
              <div className="p-6">
                <h2 className="font-blackadder text-2xl font-bold text-foreground mb-3">
                  {selectedItem.title}
                </h2>
                <p className="font-gabriola text-muted-foreground mb-4">
                  {selectedItem.description}
                </p>
                
                <div className="flex items-center justify-between text-sm text-muted-foreground">
                  <div className="flex items-center gap-4">
                    <span className="flex items-center gap-1">
                      <User className="w-4 h-4" />
                      {selectedItem.uploaded_by_first_name} {selectedItem.uploaded_by_last_name}
                    </span>
                    <span className="flex items-center gap-1">
                      <Calendar className="w-4 h-4" />
                      {formatDate(selectedItem.created_at)}
                    </span>
                  </div>
                  <div className="flex items-center gap-3">
                    <span>{selectedItem.dimensions}</span>
                    <span>{formatFileSize(selectedItem.file_size)}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};

export default GallerySection;
