import React, { useState, useEffect } from 'react';
import { Calendar, User, Eye, Heart, Clock, AlertCircle, Image as ImageIcon } from 'lucide-react';
import { API_ENDPOINTS, apiRequest } from '@/utils/api';

interface NewsArticle {
  id: number;
  title: string;
  slug: string;
  excerpt: string;
  content: string;
  featured_image_url: string | null;
  author_first_name: string;
  author_last_name: string;
  category: string | null;
  tags: string[];
  views_count: number;
  likes_count: number;
  is_featured: boolean;
  is_breaking: boolean;
  published_at: string;
  created_at: string;
}

interface NewsSectionProps {
  showBreaking?: boolean;
  limit?: number;
  category?: string;
}

const NewsSection: React.FC<NewsSectionProps> = ({ 
  showBreaking = true, 
  limit = 6, 
  category 
}) => {
  const [articles, setArticles] = useState<NewsArticle[]>([]);
  const [breakingNews, setBreakingNews] = useState<NewsArticle[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    fetchNews();
    if (showBreaking) {
      fetchBreakingNews();
    }
  }, [category, limit, showBreaking]);

  const fetchNews = async () => {
    try {
      setLoading(true);
      const url = category 
        ? `${API_ENDPOINTS.NEWS}&status=published&category=${category}&limit=${limit}`
        : `${API_ENDPOINTS.NEWS}&status=published&limit=${limit}`;
      
      const data = await apiRequest<{ success: boolean; data: { articles: NewsArticle[] } }>(url);
      
      if (data.success) {
        setArticles(data.data.articles);
      } else {
        setError('Failed to load news articles');
      }
    } catch (error) {
      setError('Network error loading news');
      console.error('Error fetching news:', error);
    } finally {
      setLoading(false);
    }
  };

  const fetchBreakingNews = async () => {
    try {
      const data = await apiRequest<{ success: boolean; data: { articles: NewsArticle[] } }>(API_ENDPOINTS.BREAKING_NEWS);
      
      if (data.success) {
        setBreakingNews(data.data.articles);
      }
    } catch (error) {
      console.error('Error fetching breaking news:', error);
    }
  };

  const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
  };

  const truncateText = (text: string, maxLength: number) => {
    if (text.length <= maxLength) return text;
    return text.substring(0, maxLength) + '...';
  };

  if (loading) {
    return (
      <div className="space-y-6">
        {showBreaking && (
          <div className="glassmorphism-enhanced rounded-2xl p-6 animate-pulse">
            <div className="h-4 bg-gold/20 rounded w-1/4 mb-2"></div>
            <div className="h-6 bg-gold/20 rounded w-3/4"></div>
          </div>
        )}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {[...Array(limit)].map((_, i) => (
            <div key={i} className="glassmorphism-enhanced rounded-2xl overflow-hidden animate-pulse">
              <div className="h-48 bg-gold/20"></div>
              <div className="p-6 space-y-3">
                <div className="h-4 bg-gold/20 rounded w-3/4"></div>
                <div className="h-3 bg-gold/20 rounded w-full"></div>
                <div className="h-3 bg-gold/20 rounded w-2/3"></div>
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
        <AlertCircle className="w-12 h-12 text-gold mx-auto mb-4" />
        <h3 className="text-xl font-semibold text-foreground mb-2">Unable to Load News</h3>
        <p className="text-muted-foreground mb-4">{error}</p>
        <button 
          onClick={fetchNews}
          className="px-6 py-2 bg-gradient-gold text-primary font-semibold rounded-lg hover:opacity-90 transition"
        >
          Try Again
        </button>
      </div>
    );
  }

  return (
    <div className="space-y-8">
      {/* Breaking News Banner */}
      {showBreaking && breakingNews.length > 0 && (
        <div className="glassmorphism-enhanced rounded-2xl p-6 border-l-4 border-red-500 animate-slide-in-right">
          <div className="flex items-center gap-3 mb-4">
            <div className="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
            <span className="text-red-500 font-bold text-sm uppercase tracking-wider">Breaking News</span>
          </div>
          <div className="space-y-3">
            {breakingNews.map((article) => (
              <div key={article.id} className="flex items-start gap-4">
                {article.featured_image_url && (
                  <img 
                    src={article.featured_image_url} 
                    alt={article.title}
                    className="w-20 h-20 object-cover rounded-lg flex-shrink-0"
                  />
                )}
                <div className="flex-1">
                  <h3 className="font-semibold text-foreground mb-1 hover:text-gold transition cursor-pointer">
                    {article.title}
                  </h3>
                  <p className="text-sm text-muted-foreground line-clamp-2">
                    {article.excerpt}
                  </p>
                </div>
              </div>
            ))}
          </div>
        </div>
      )}

      {/* News Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {articles.map((article) => (
          <article 
            key={article.id} 
            className="glassmorphism-enhanced rounded-2xl overflow-hidden border border-white/10 shadow-gold-enhanced card-hover-3d group"
          >
            {/* Featured Image */}
            <div className="relative h-48 overflow-hidden">
              {article.featured_image_url ? (
                <img 
                  src={article.featured_image_url} 
                  alt={article.title}
                  className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                />
              ) : (
                <div className="w-full h-full bg-gradient-to-br from-gold/20 to-navy/20 flex items-center justify-center">
                  <ImageIcon className="w-12 h-12 text-gold/50" />
                </div>
              )}
              
              {/* Badges */}
              <div className="absolute top-3 left-3 flex gap-2">
                {article.is_featured && (
                  <span className="px-2 py-1 bg-gold text-primary text-xs font-semibold rounded-full">
                    Featured
                  </span>
                )}
                {article.is_breaking && (
                  <span className="px-2 py-1 bg-red-500 text-white text-xs font-semibold rounded-full">
                    Breaking
                  </span>
                )}
                {article.category && (
                  <span className="px-2 py-1 bg-navy text-white text-xs font-semibold rounded-full">
                    {article.category}
                  </span>
                )}
              </div>
            </div>

            {/* Content */}
            <div className="p-6">
              <h3 className="font-blackadder text-xl font-bold text-foreground mb-3 line-clamp-2 group-hover:text-gradient-gold transition">
                {article.title}
              </h3>
              
              <p className="font-gabriola text-muted-foreground text-sm mb-4 line-clamp-3">
                {article.excerpt}
              </p>

              {/* Meta Information */}
              <div className="flex items-center justify-between text-xs text-muted-foreground mb-4">
                <div className="flex items-center gap-3">
                  <span className="flex items-center gap-1">
                    <User className="w-3 h-3" />
                    {article.author_first_name} {article.author_last_name}
                  </span>
                  <span className="flex items-center gap-1">
                    <Calendar className="w-3 h-3" />
                    {formatDate(article.published_at)}
                  </span>
                </div>
              </div>

              {/* Stats */}
              <div className="flex items-center justify-between pt-4 border-t border-white/10">
                <div className="flex items-center gap-4 text-xs text-muted-foreground">
                  <span className="flex items-center gap-1">
                    <Eye className="w-3 h-3" />
                    {article.views_count}
                  </span>
                  <span className="flex items-center gap-1">
                    <Heart className="w-3 h-3" />
                    {article.likes_count}
                  </span>
                </div>
                <button className="text-gold hover:text-gold/80 transition text-sm font-semibold">
                  Read More →
                </button>
              </div>
            </div>
          </article>
        ))}
      </div>

      {/* Load More Button */}
      {articles.length > 0 && (
        <div className="text-center">
          <button className="inline-flex items-center px-8 py-3 bg-gradient-gold text-primary font-semibold rounded-lg shadow-gold-enhanced hover:scale-105 transition">
            Load More Articles
          </button>
        </div>
      )}

      {/* Empty State */}
      {articles.length === 0 && !loading && (
        <div className="glassmorphism-enhanced rounded-2xl p-12 text-center">
          <ImageIcon className="w-16 h-16 text-gold/50 mx-auto mb-4" />
          <h3 className="text-xl font-semibold text-foreground mb-2">No News Articles</h3>
          <p className="text-muted-foreground">
            {category ? `No articles found in ${category} category.` : 'No news articles available at the moment.'}
          </p>
        </div>
      )}
    </div>
  );
};

export default NewsSection;
